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
        clientId: `mqtt_bridge_absensi_${Math.random().toString(16).substr(2, 8)}`,
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
            await handleAbsensi(payload);
        }

        if (topic === TOPICS.registerRequest) {
            log("📝 Register request received from web (ESP8266 will handle)");
        }
    } catch (error) {
        logError("Error processing MQTT message", error);
    }
});

// ===== ABSENSI HANDLER (ABSENSI ONLY - AUTO GRANT) =====
async function handleAbsensi(payload) {
    const { rfid_card, timestamp } = payload;

    if (!rfid_card) {
        logError("Invalid payload: missing rfid_card");
        return;
    }

    log(`📋 Recording attendance for RFID: ${rfid_card}`);

    try {
        // Call Laravel API Absensi
        const response = await axios.post(
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
                timeout: 5000,
            },
        );

        const apiData = response.data;
        log("✅ API Absensi Response received", apiData);

        let mqttResponse = {
            status: "DENIED",
            message: "Absensi Failed",
            timestamp: new Date().toISOString(),
        };

        // Check response dari API Absensi
        if (apiData.success || apiData.status === "success") {
            mqttResponse = {
                status: "GRANTED",
                nama: apiData.data?.anggota || apiData.data?.nama || "Unknown",
                rfid_card: rfid_card,
                timestamp: timestamp,
                message: apiData.message || "Absensi Recorded",
            };
            log(`✅ Absensi SUCCESS for: ${rfid_card}`);
        } else {
            // Untuk error 409 atau duplicate absensi
            mqttResponse.message = apiData.message || "RFID not registered";
            mqttResponse.nama = apiData.data?.anggota || "Unknown";
            log(`❌ Absensi FAILED: ${mqttResponse.message}`);
        }

        // Publish response to ESP8266 (auto open door for attendance)
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

        // Handle error response dengan data
        let errorResponse = {
            status: "DENIED",
            message: "Server Error",
            timestamp: new Date().toISOString(),
        };

        // Jika ada response data dari API (seperti 409 duplicate)
        if (error.response && error.response.data) {
            const errorData = error.response.data;
            errorResponse.message = errorData.message || "Server Error";
            errorResponse.nama = errorData.data?.anggota || "Unknown";

            log(`ℹ️  Error details: ${errorResponse.message}`, errorData);
        } else {
            errorResponse.error = error.message;
        }

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
console.log("║   ABSENSI ONLY SERVER                          ║");
console.log("║   Attendance Recording (Auto Open Door)        ║");
console.log("╚════════════════════════════════════════════════╝");
console.log("");
log("🚀 Starting MQTT Bridge Server...");
log(`📡 MQTT Broker: ${MQTT_CONFIG.host}:${MQTT_CONFIG.port}`);
log(`🌐 API Base URL: ${API_CONFIG.baseURL}`);
log(`🔧 Mode: ABSENSI ONLY (Auto Grant)`);
log(`🔧 Debug Mode: ${DEBUG ? "ON" : "OFF"}`);
console.log("");
