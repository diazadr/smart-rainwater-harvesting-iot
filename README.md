# Smart Rainwater Harvesting IoT

![Project Status](https://img.shields.io/badge/status-completed-brightgreen) [![License](https://img.shields.io/badge/license-MIT-blue)](./LICENSE)

This project is the result of our Week 2 Komunikasi Data (IIoT) assignment, focused on the development of a **Smart Rainwater Harvesting System** using ESP8266, MQTT, Laravel, and Tailwind CSS. It enables real-time monitoring of environmental conditions and intelligent pump control based on water tank levels and temperature data. Communication is managed via the MQTT protocol using the Shiftr.io cloud broker.

## **Technologies Used**

* ESP8266 (NodeMCU)
* MQTT (via Shiftr.io)
* DHT22 Sensor
* Ultrasonic Sensor (HC-SR04)
* Relay Module
* Laravel 11 (Backend)
* Tailwind CSS (Frontend)

## **Features**

* **Real-time Monitoring**: Displays temperature, humidity, and water level in real-time.
* **Smart Pump Control**: Automatically activates the pump if temperature > 30°C and allows manual control via the website.
* **Tank Status**: Shows water status (Empty, Medium, Full) based on the ultrasonic sensor.
* **MQTT Communication**: Enables communication between ESP8266 and web server using the MQTT protocol.
* **Web Dashboard**: Monitor and control the system via a web interface built with Laravel and Tailwind CSS.

## **Architecture**

![architecture-diagram](https://github.com/user-attachments/assets/5f7ae7e3-a68e-4487-82fb-e3e7cd6de409)

![mqtt-flow-diagram](https://github.com/user-attachments/assets/82fafd81-4479-408d-8a62-7e57f11791fe)

## **Schematic**

![schematic-diagram](https://github.com/user-attachments/assets/945a6479-8789-476a-a02c-9794a2714894)

## **Demo**

### **Serial Monitor Arduino**

<img src="https://github.com/user-attachments/assets/97efb102-c030-4e07-ae00-cdc2a534953b" alt="serial-monitor" style="width: 600px; height: auto;">

### **Website Sensor Data**

![website-sensor-data](https://github.com/user-attachments/assets/2c7987fd-6d69-4403-9001-1a3f539425c7)

### **Website Dashboard**

<img src="https://github.com/user-attachments/assets/0d9bb901-cc07-486f-b2e5-04b8ec7c848a" alt="website-dashboard-1" style="width: 600px; height: auto;">

![website-dashboard-2](https://github.com/user-attachments/assets/0c60d8a8-b969-446e-bfbc-205fffe2b5cc)

![website-dashboard-3](https://github.com/user-attachments/assets/d0fb2eba-0cb3-4995-a0f5-0c8a37419dab)

![website-dashboard-4](https://github.com/user-attachments/assets/b4063047-b986-49a5-9ba2-9e56fbd32e76)

### **Prototype**

House prototype:

![prototype-house](https://github.com/user-attachments/assets/e4439b0d-658f-437c-ad89-70ed8159b784)

Pump and wiring:

![prototype-pump-wiring](https://github.com/user-attachments/assets/59c4638c-3f23-47af-88d6-83efa1462ad6)

### **Cloud Shiftr.io**

<img src="https://github.com/user-attachments/assets/957f6b92-b9a8-434a-bce9-a820d160c4f3" alt="shiftr-dashboard" style="width: 600px; height: auto;">

## **Setup**

1. **Clone the repository**

   ```sh
   git clone https://github.com/diazadr/advanced-web-iot.git
   cd advanced-web-iot
   ```

2. **Install dependencies**

   ```sh
   composer install
   ```

3. **Configure environment variables**

   ```sh
   cp .env.example .env
   php artisan key:generate
   ```

   * Update the following in `.env`:

     ```env
     DB_DATABASE=your_database_name
     DB_USERNAME=your_db_user
     DB_PASSWORD=your_db_password
     ```

4. **Run database migrations**

   ```sh
   php artisan migrate
   ```

   Or import SQL file manually (if provided):

   ```sh
   mysql -u your_db_user -p your_database_name < database/import.sql
   ```

5. **Start the Laravel server**

   ```sh
   php artisan serve
   ```

6. **Flash the ESP8266 firmware** with the provided `.ino` file using the Arduino IDE.

7. **Connect ESP8266 to Wi-Fi and MQTT broker** (credentials are inside the code):

   ```cpp
   const char* ssid = "YOUR_WIFI_SSID";
   const char* password = "YOUR_WIFI_PASSWORD";
   const char* mqtt_server = "broker.shiftr.io";
   const char* mqtt_user = "your_username";
   const char* mqtt_pass = "your_password";
   ```

## **Usage**

1. Power the ESP8266 and wait for sensor values to be published to MQTT.
2. Use the Laravel dashboard to view data and manually control the relay.
3. MQTT topics used:

   * `iot/water/temp` - Temperature
   * `iot/water/humidity` - Humidity
   * `iot/water/level` - Tank level
   * `iot/water/pump` - Pump control (publish `ON` or `OFF`)

## **Acknowledgments**

Special thanks to **Group 2**:

* **Azka Shafa Eka Poetra**
* **Bayu Putra Pamungkas**

For their significant contributions in building the **prototype** and designing the system **layout**.

## **Project Status**

This project is **completed** and will not be further developed.

## **Contributions**

Contributions are welcome! Feel free to open issues or submit pull requests.

## **License**

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.
