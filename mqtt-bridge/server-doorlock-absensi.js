require("dotenv").config();
const mqtt = require("mqtt");
const axios = require("axios");

// ===== CONFIGURATION =====
const MQTT_CONFIG = {
    host: process.env.MQTT_BROKER_HOST,
    port: parseInt(process.env.MQTT_BROKER_PORT),
    username: process.env.MQTT_USERNAME,
    password: process.env.MQTT_PASSWORD,
};

const API_CONFIG = {
    baseURL: process.env.API_BASE_URL,
    doorLockEndpoint: process.env.API_DOOR_LOCK_ENDPOINT,
    absensiEndpoint: process.env.API_ABSENSI_ENDPOINT,
};

const TOPICS = {
    accessCheck: process.env.TOPIC_ACCESS_CHECK,
    accessResponse: process.env.TOPIC_ACCESS_RESPONSE,
    registerRequest: process.env.TOPIC_REGISTER_REQUEST,
    registerResponse: process.env.TOPIC_REGISTER_RESPONSE,
};

const DEBUG = process.env.DEBUG === "true";

// ===== MQTT CLIENT SETUP =====
const mqttClient = mqtt.connect(
    `mqtt://${MQTT_CONFIG.host}:${MQTT_CONFIG.port}`,
    {
        username: MQTT_CONFIG.username,
        password: MQTT_CONFIG.password,
        clientId: `mqtt_bridge_doorlock_absensi_${Math.random().toString(16).substr(2, 8)}`,
        clean: true,
        reconnectPeriod: 1000,
    },
);

// ===== HELPER FUNCTIONS =====
function log(message, data = null) {
    const timestamp = new Date().toISOString();
    console.log(`[${timestamp}] ${message}`);
    if (DEBUG && data) {
        console.log(JSON.stringify(data, null, 2));
    }
}

function logError(message, error) {
    const timestamp = new Date().toISOString();
    console.error(`[${timestamp}] ERROR: ${message}`);
    if (error) {
        console.error(error.message);
        if (DEBUG && error.response) {
            console.error("Response:", error.response.data);
        }
    }
}

// ===== MQTT EVENT HANDLERS =====
mqttClient.on("connect", () => {
    log("✅ Connected to MQTT Broker");
    log(`📍 Host: ${MQTT_CONFIG.host}:${MQTT_CONFIG.port}`);

    mqttClient.subscribe(TOPICS.accessCheck, (err) => {
        if (!err) {
            log(`📥 Subscribed to: ${TOPICS.accessCheck}`);
        } else {
            logError("Failed to subscribe to access check topic", err);
        }
    });

    mqttClient.subscribe(TOPICS.registerRequest, (err) => {
        if (!err) {
            log(`📥 Subscribed to: ${TOPICS.registerRequest}`);
        } else {
            logError("Failed to subscribe to register request topic", err);
        }
    });
});

mqttClient.on("error", (error) => {
    logError("MQTT Connection Error", error);
});

mqttClient.on("reconnect", () => {
    log("🔄 Reconnecting to MQTT Broker...");
});

mqttClient.on("offline", () => {
    log("⚠️  MQTT Client is offline");
});

// ===== MQTT MESSAGE HANDLER =====
mqttClient.on("message", async (topic, message) => {
    try {
        const payload = JSON.parse(message.toString());
        log(`📨 Message received on topic: ${topic}`, payload);

        if (topic === TOPICS.accessCheck) {
            await handleAccessCheck(payload);
        }

        if (topic === TOPICS.registerRequest) {
            log("📝 Register request received from web (ESP8266 will handle)");
        }
    } catch (error) {
        logError("Error processing MQTT message", error);
    }
});

// ===== ACCESS CHECK HANDLER (DOOR LOCK + ABSENSI) =====
async function handleAccessCheck(payload) {
    const { rfid_card, timestamp } = payload;

    if (!rfid_card) {
        logError("Invalid payload: missing rfid_card");
        return;
    }

    log(`🔍 Checking access for RFID: ${rfid_card}`);

    try {
        // Call Laravel API Door Lock
        const response = await axios.post(
            `${API_CONFIG.baseURL}${API_CONFIG.doorLockEndpoint}`,
            {
                rfid_card: rfid_card,
                timestamp: timestamp,
            },
            {
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
                timeout: 5000,
            },
        );

        const apiData = response.data;
        log("✅ API Door Lock Response received", apiData);

        let mqttResponse = {
            status: "DENIED",
            message: "Access Denied",
            timestamp: new Date().toISOString(),
        };

        if (apiData.access_status === "GRANTED" && apiData.data) {
            mqttResponse = {
                status: "GRANTED",
                nama: apiData.data.anggota || "Unknown",
                rfid_card: rfid_card,
                timestamp: timestamp,
                message: apiData.message || "Access Granted",
            };
            log(`✅ Access GRANTED for: ${mqttResponse.nama}`);

            // AUTO RECORD ABSENSI
            try {
                const absensiResponse = await axios.post(
                    `${API_CONFIG.baseURL}${API_CONFIG.absensiEndpoint}`,
                    {
                        rfid_card: rfid_card,
                        timestamp: timestamp,
                    },
                    {
                        headers: {
                            "Content-Type": "application/json",
                            Accept: "application/json",
                        },
                        timeout: 3000,
                    },
                );
                log("✅ Absensi recorded successfully", absensiResponse.data);
            } catch (absensiError) {
                logError(
                    "⚠️  Failed to record absensi (non-critical)",
                    absensiError,
                );
            }
        } else {
            mqttResponse.message =
                apiData.message || "RFID Card not registered";
            log(`❌ Access DENIED: ${mqttResponse.message}`);
        }

        // Publish response to ESP8266
        mqttClient.publish(
            TOPICS.accessResponse,
            JSON.stringify(mqttResponse),
            { qos: 1 },
            (err) => {
                if (!err) {
                    log(
                        `📤 Response published to: ${TOPICS.accessResponse}`,
                        mqttResponse,
                    );
                } else {
                    logError("Failed to publish response", err);
                }
            },
        );
    } catch (error) {
        logError("API Request Failed", error);

        const errorResponse = {
            status: "DENIED",
            message: "Server Error",
            error: error.message,
            timestamp: new Date().toISOString(),
        };

        mqttClient.publish(
            TOPICS.accessResponse,
            JSON.stringify(errorResponse),
            { qos: 1 },
        );
    }
}

// ===== GRACEFUL SHUTDOWN =====
process.on("SIGINT", () => {
    log("🛑 Shutting down gracefully...");
    mqttClient.end(() => {
        log("👋 MQTT connection closed");
        process.exit(0);
    });
});

process.on("SIGTERM", () => {
    log("🛑 Received SIGTERM signal");
    mqttClient.end(() => {
        log("👋 MQTT connection closed");
        process.exit(0);
    });
});

// ===== STARTUP MESSAGE =====
console.log("╔════════════════════════════════════════════════╗");
console.log("║   DOOR LOCK + ABSENSI SERVER                   ║");
console.log("║   Auto Door Access & Attendance Recording      ║");
console.log("╚════════════════════════════════════════════════╝");
console.log("");
log("🚀 Starting MQTT Bridge Server...");
log(`📡 MQTT Broker: ${MQTT_CONFIG.host}:${MQTT_CONFIG.port}`);
log(`🌐 API Base URL: ${API_CONFIG.baseURL}`);
log(`🔧 Mode: DOOR LOCK + ABSENSI (Auto)`);
log(`🔧 Debug Mode: ${DEBUG ? "ON" : "OFF"}`);
console.log("");
