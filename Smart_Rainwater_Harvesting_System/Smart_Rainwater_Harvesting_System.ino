#include <ESP8266WiFi.h>
#include <MQTT.h>
#include <DHT.h>

const char* ssid = "Rainwater Harvesting";
const char* pass = "rainwater123";
WiFiClient net;
MQTTClient client;

#define DHTPIN D5
#define DHTTYPE DHT22
DHT dht(DHTPIN, DHTTYPE);

#define RELAY_PIN D6
#define RELAY_ON LOW
#define RELAY_OFF HIGH
bool relayStatus = false;
bool manualControl = false;

#define TRIG_PIN D7
#define ECHO_PIN D8

void setRelay(bool on) {
  digitalWrite(RELAY_PIN, on ? RELAY_ON : RELAY_OFF);
  relayStatus = on;
  client.publish("rainwater/pompa/status", relayStatus ? "ON" : "OFF", true, 1);
  Serial.print("Relay: "); Serial.println(relayStatus ? "ON" : "OFF");
}

float readDistanceCM() {
  digitalWrite(TRIG_PIN, LOW);
  delayMicroseconds(2);
  digitalWrite(TRIG_PIN, HIGH);
  delayMicroseconds(10);
  digitalWrite(TRIG_PIN, LOW);
  long duration = pulseIn(ECHO_PIN, HIGH, 30000);
  float distance = duration * 0.034 / 2;
  return distance;
}

void publishSensor() {
  float suhu = dht.readTemperature();
  float kelembapan = dht.readHumidity();
  float jarak = readDistanceCM();

  if (isnan(suhu) || isnan(kelembapan)) {
    Serial.println("Gagal baca DHT!");
    return;
  }

  client.publish("rainwater/suhu", String(suhu, 1), true, 1);
  client.publish("rainwater/kelembapan", String(kelembapan, 1), true, 1);
  client.publish("rainwater/tabung/jarak", String(jarak, 1), true, 1);

  Serial.printf("Suhu: %.1f °C | Kelembapan: %.1f %% | Jarak: %.1f cm\n", suhu, kelembapan, jarak);

  if (jarak > 14.0) {
    client.publish("rainwater/tabung/status", "Kosong", true, 1);
    Serial.println("Status Air: Kosong");
  } else if (jarak > 6.0 && jarak <= 14.0) {
    client.publish("rainwater/tabung/status", "Sedang", true, 1);
    Serial.println("Status Air: Sedang");
  } else if (jarak <= 6.0) {
    client.publish("rainwater/tabung/status", "Penuh", true, 1);
    Serial.println("Status Air: Penuh");
  }

  if (!manualControl) {
    if (suhu > 30 && !relayStatus) {
      setRelay(true);
    } else if (suhu <= 30 && relayStatus) {
      setRelay(false);
    }
  }
}

void messageReceived(String &topic, String &payload) {
  Serial.println("MQTT: " + topic + " = " + payload);
  if (topic == "rainwater/pompa/set") {
    manualControl = true;
    if (payload == "ON") {
      setRelay(true);
    } else if (payload == "OFF") {
      setRelay(false);
    }
  } else if (topic == "rainwater/pompa/auto") {
    manualControl = false;
    Serial.println("Mode otomatis aktif");
  }
}

void connect() {
  Serial.print("WiFi...");
  while (WiFi.status() != WL_CONNECTED) {
    Serial.print(".");
    delay(1000);
  }
  Serial.println("Terhubung!");

  Serial.print("MQTT...");
  while (!client.connect("5678", "learniot", "Q9kct1p8Qw7l9G9C")) {
    Serial.print(".");
    delay(1000);
  }
  Serial.println("Terhubung!");

  client.subscribe("rainwater/#", 1);
  client.publish("rainwater/serial_number/5678", "Online", true, 1);
}

void setup() {
  Serial.begin(115200);
  WiFi.begin(ssid, pass);

  pinMode(RELAY_PIN, OUTPUT);
  digitalWrite(RELAY_PIN, RELAY_OFF);

  pinMode(TRIG_PIN, OUTPUT);
  pinMode(ECHO_PIN, INPUT);

  dht.begin();

  client.begin("learniot.cloud.shiftr.io", net);
  client.onMessage(messageReceived);
  client.setWill("rainwater/serial_number/5678", "Offline", true, 1);
  connect();
}

unsigned long lastPublish = 0;

void loop() {
  client.loop();
  if (!client.connected()) {
    connect();
  }

  if (millis() - lastPublish > 2000) {
    lastPublish = millis();
    publishSensor();
  }
}
