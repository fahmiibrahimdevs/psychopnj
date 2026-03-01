# MQTT Bridge Server - Psychorobotic Door Lock

Bridge antara ESP8266 Door Lock System dan Laravel API menggunakan MQTT protocol.

## Features

- ✅ Subscribe ke MQTT topic untuk access check dari ESP8266
- ✅ Kirim request ke Laravel API `/api/door-lock`
- ✅ Auto record absensi ke `/api/absensi`
- ✅ Publish response (GRANTED/DENIED) kembali ke ESP8266
- ✅ Automatic reconnection
- ✅ Error handling & logging
- ✅ Timestamp dengan format YYYY-MM-DD HH:MM:SS

## Installation

```bash
cd mqtt-bridge
npm install
```

## Configuration

Edit file `.env` sesuai kebutuhan:

```env
MQTT_BROKER_HOST=103.75.209.90
MQTT_BROKER_PORT=1883
MQTT_USERNAME=nexaryn
MQTT_PASSWORD=31750321

API_BASE_URL=http://psychorobotic.test
```

## Usage

### Development Mode (dengan auto-reload)

```bash
npm run dev
```

### Production Mode

```bash
npm start
```

## Flow Diagram

```
ESP8266 --[scan RFID]--> MQTT (access/check)
                              ↓
                         Node.js Bridge
                              ↓
                    Laravel API (/api/door-lock)
                              ↓
                    Response (GRANTED/DENIED)
                              ↓
                         MQTT (access/response)
                              ↓
ESP8266 <--[unlock door]-- Response
```

## MQTT Topics

- **Subscribe:**
    - `psychorobotic/doorlock/access/check` - Dari ESP8266
    - `psychorobotic/rfid/register/request` - Dari Web UI
- **Publish:**
    - `psychorobotic/doorlock/access/response` - Ke ESP8266
    - `psychorobotic/rfid/register/response` - Dari ESP8266 ke Web

## Message Format

### Access Check (ESP8266 → Bridge)

```json
{
    "rfid_card": "ABCD1234",
    "timestamp": "2026-02-17 18:46:50"
}
```

### Access Response (Bridge → ESP8266)

```json
{
    "status": "GRANTED",
    "nama": "Muhammad Syafiq Aziz",
    "rfid_card": "ABCD1234",
    "timestamp": "2026-02-17 18:46:50",
    "message": "Akses diberikan"
}
```

## Logs

Server akan menampilkan log real-time:

- ✅ Connection status
- 📥 Subscribed topics
- 📨 Incoming messages
- 🔍 API requests
- 📤 Published responses
- ❌ Errors

## Auto-start dengan PM2 (Optional)

```bash
npm install -g pm2
pm2 start server.js --name mqtt-bridge
pm2 save
pm2 startup
```

## Troubleshooting

### Connection Failed

- Cek MQTT broker credentials di `.env`
- Pastikan port 1883 tidak diblok firewall

### API Error

- Pastikan Laravel server running
- Cek `API_BASE_URL` di `.env`
- Verify endpoint `/api/door-lock` accessible

### No Response from ESP8266

- Cek ESP8266 subscribe ke topic `access/response`
- Verify MQTT QoS settings
