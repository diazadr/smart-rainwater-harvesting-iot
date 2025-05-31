<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Rainwater Harvesting</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .water-tank {
            height: 200px;
            width: 150px;
            border: 3px solid #3b82f6;
            border-radius: 0 0 10px 10px;
            position: relative;
            overflow: hidden;
            background-color: #f0f9ff;
            margin: 0 auto;
        }

        .water-level {
            position: absolute;
            bottom: 0;
            width: 100%;
            background-color: #60a5fa;
            transition: height 0.5s ease;
        }

        .led-indicator {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: inline-block;
            margin: 0 2px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        }

        .led-red {
            background-color: #ff0000;
        }

        .led-green {
            background-color: #00ff00;
        }

        .led-blue {
            background-color: #0000ff;
        }

        .led-off {
            background-color: #cccccc;
        }
    </style>
</head>

<body class="bg-gray-100">
    <!-- Dashboard Utama -->
    <div class="min-h-screen">
        <!-- Bagian Judul -->
        <div class="text-center py-8">
            <h1 class="text-4xl font-bold text-blue-800 mb-2">Smart Rainwater Harvesting</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Sistem IoT untuk memantau level air, suhu, dan kelembapan dengan kontrol pompa otomatis.
                Pompa akan aktif ketika suhu >30°C dan air tersedia.
            </p>
            <div
                class="mt-4 flex flex-col md:flex-row md:items-center md:justify-center space-y-2 md:space-y-0 md:space-x-4">
                @auth
                    <span class="text-gray-600 text-center md:text-left">Selamat datang,
                        <span class="font-semibold">{{ auth()->user()->name }}</span>
                    </span>

                    <!-- Tombol ke halaman sensor -->
                    <a href="/sensor"
                        class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition duration-200 text-center">
                        <i class="fas fa-sliders-h mr-2"></i>Sensor
                    </a>

                    <!-- Tombol logout -->
                    <a href="{{ route('logout') }}"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200 text-center"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt mr-2"></i>Keluar
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg transition duration-200 text-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>Masuk untuk Kontrol Pompa
                    </a>
                @endauth

                <!-- Tombol Aquarium -->
                <a href="/tentang"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200 text-center">
                    <i class="fas fa-fish mr-2"></i>Aquarium
                </a>
            </div>

        </div>

        <!-- Konten Dashboard -->
        <main class="container mx-auto px-4 py-6">
            <!-- Kartu Sensor -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Kartu Suhu -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition duration-200">
                    <div class="p-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-700">Suhu</h3>
                                <p id="suhu" class="text-3xl font-bold text-red-500 mt-2">?°C</p>
                                <p id="temp-status" class="text-sm font-medium mt-1">-</p>
                                <p class="text-xs text-gray-500 mt-1">Pompa ON >30°C</p>
                            </div>
                            <div class="bg-red-100 p-3 rounded-full">
                                <i class="fas fa-temperature-high text-red-500 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition duration-200">
                    <div class="p-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-700">Kelembapan</h3>
                                <p id="kelembapan" class="text-3xl font-bold text-blue-500 mt-2">?%</p>
                                <p id="humidity-status" class="text-sm text-gray-500 mt-1">-</p>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-full">
                                <i class="fas fa-tint text-blue-500 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kartu Level Air -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition duration-200">
                    <div class="p-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-700">Level Air</h3>
                                <p id="jarak" class="text-3xl font-bold text-green-500 mt-2">? cm</p>
                                <div class="flex items-center mt-1">
                                    <span id="water-status" class="text-sm mr-2">-</span>
                                    <span id="led-r" class="led-indicator led-off"></span>
                                    <span id="led-g" class="led-indicator led-off"></span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">>14cm:Kosong | 6-14cm:Medium | ≤6cm:Penuh</p>
                            </div>
                            <div class="bg-green-100 p-3 rounded-full">
                                <i class="fas fa-ruler-vertical text-green-500 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition duration-200">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-700">Status Pompa</h3>
                                <p id="pump-status" class="text-3xl font-bold mt-2">?</p>
                                <p id="pump-action" class="text-sm text-gray-500 mt-1">-</p>
                            </div>
                            <div class="bg-gray-100 p-3 rounded-full">
                                <i class="fas fa-water text-gray-500 text-2xl"></i>
                            </div>
                        </div>
                        @auth
                            <div class="space-y-2">
                                <button id="btn-on"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition duration-200">
                                    <i class="fas fa-power-off mr-2"></i>Nyalakan
                                </button>
                                <button id="btn-off"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg transition duration-200">
                                    <i class="fas fa-power-off mr-2"></i>Matikan
                                </button>
                                <button id="btn-auto"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition duration-200">
                                    <i class="fas fa-robot mr-2"></i>Mode Otomatis
                                </button>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md overflow-hidden p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Visualisasi Tangki Air</h2>
                <div class="flex flex-col md:flex-row items-center justify-around">
                    <div class="text-center mb-6 md:mb-0">
                        <div class="water-tank">
                            <div id="water-level" class="water-level" style="height: 0%"></div>
                        </div>
                        <p id="tank-percentage" class="mt-2 font-medium">0% terisi</p>
                    </div>

                    <div class="text-center">
                        <h3 class="text-lg font-semibold mb-2">Status Sistem</h3>
                        <div class="mb-4">
                            <p id="tank-status-text" class="text-sm mb-1">Tangki: -</p>
                            <div class="flex justify-center">
                                <span id="vis-led-r" class="led-indicator led-off"></span>
                                <span id="vis-led-g" class="led-indicator led-off"></span>
                            </div>
                        </div>

                        <div class="mt-4">
                            <p id="pump-indicator-text" class="text-sm">Pompa: Offline</p>
                            <div id="pump-visual" class="w-8 h-8 mx-auto mt-2 rounded-full bg-gray-200"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Status Perangkat</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID Perangkat</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($devices as $item)
                                @endforeach
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $item->serial_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span id="rainwater/serial_number/5678"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                            <span class="w-2 h-2 mr-1 rounded-full bg-gray-300"></span>
                                            {{ $item->serial_number }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        const clientId = Math.random().toString(16).substring(2, 8);
        const host = "wss://learniot.cloud.shiftr.io:443/mqtt";
        const options = {
            keepalive: 30,
            clientId: clientId,
            protocolId: 'MQTT',
            protocolVersion: 4,
            username: 'learniot',
            password: '2BV7aBDzdnEsfSMF',
            clean: true,
            reconnectPeriod: 1000,
            connectTimeout: 30 * 1000,
        };


        const maxTankDistance = 20;

        console.log("Menghubungkan ke broker");
        const client = mqtt.connect(host, options);

        client.on("connect", () => {
            console.log("Terhubung ke broker");
            client.subscribe("rainwater/#", {
                qos: 1
            });
        });

        client.on("message", (topic, message) => {
            const msg = message.toString();
            console.log("Diterima:", topic, msg);

            if (topic === "rainwater/suhu") {
                const temp = parseFloat(msg);
                document.getElementById("suhu").textContent = msg + "°C";

                const tempStatus = document.getElementById("temp-status");

                if (temp > 30) {
                    tempStatus.textContent = "Panas (Pompa mungkin aktif)";
                    tempStatus.className = "text-sm font-medium mt-1 text-red-500";
                } else {
                    tempStatus.textContent = "Dingin/Musim Hujan";
                    tempStatus.className = "text-sm font-medium mt-1 text-blue-500";
                }
            }

            if (topic === "rainwater/kelembapan") {
                document.getElementById("kelembapan").textContent = msg + "%";
                const humidity = parseFloat(msg);
                const humidityStatus = document.getElementById("humidity-status");

                if (humidity > 70) {
                    humidityStatus.textContent = "Udara Lembap";
                    humidityStatus.className = "text-sm text-blue-500 mt-1";
                } else if (humidity > 40) {
                    humidityStatus.textContent = "Kelembapan Normal";
                    humidityStatus.className = "text-sm text-green-500 mt-1";
                } else {
                    humidityStatus.textContent = "Udara Kering";
                    humidityStatus.className = "text-sm text-orange-500 mt-1";
                }
            }

            if (topic === "rainwater/tabung/jarak") {
                const distance = parseFloat(msg);
                document.getElementById("jarak").textContent = msg + " cm";

                const waterLevel = Math.max(0, Math.min(100, 100 - (distance / maxTankDistance * 100)));
                document.getElementById("water-level").style.height = waterLevel + "%";
                document.getElementById("tank-percentage").textContent = Math.round(waterLevel) + "% terisi";

                const waterStatus = document.getElementById("water-status");
                const tankStatusText = document.getElementById("tank-status-text");
                const ledR = document.getElementById("led-r");
                const ledG = document.getElementById("led-g");
                const visLedR = document.getElementById("vis-led-r");
                const visLedG = document.getElementById("vis-led-g");

                if (distance > 14.0) {
                    waterStatus.textContent = "Kosong";
                    waterStatus.className = "text-sm text-red-500 mr-2";
                    tankStatusText.textContent = "Tangki: Kosong";
                    tankStatusText.className = "text-sm text-red-500 mb-1";

                    ledR.className = "led-indicator led-red";
                    ledG.className = "led-indicator led-off";
                    visLedR.className = "led-indicator led-red";
                    visLedG.className = "led-indicator led-off";
                } else if (distance > 6.0 && distance <= 14.0) {
                    waterStatus.textContent = "Medium";
                    waterStatus.className = "text-sm text-yellow-500 mr-2";
                    tankStatusText.textContent = "Tangki: Medium";
                    tankStatusText.className = "text-sm text-yellow-500 mb-1";

                    ledR.className = "led-indicator led-red";
                    ledG.className = "led-indicator led-green";
                    visLedR.className = "led-indicator led-red";
                    visLedG.className = "led-indicator led-green";
                } else if (distance <= 6.0) {
                    waterStatus.textContent = "Penuh";
                    waterStatus.className = "text-sm text-green-500 mr-2";
                    tankStatusText.textContent = "Tangki: Penuh";
                    tankStatusText.className = "text-sm text-green-500 mb-1";

                    ledR.className = "led-indicator led-off";
                    ledG.className = "led-indicator led-green";
                    visLedR.className = "led-indicator led-off";
                    visLedG.className = "led-indicator led-green";
                }
            }

            if (topic === "rainwater/tabung/status") {
                const tankStatusText = document.getElementById("tank-status-text");
                tankStatusText.textContent = "Tangki: " + msg;

                if (msg === "Kosong") {
                    tankStatusText.className = "text-sm text-red-500 mb-1";
                } else if (msg === "Sedang") {
                    tankStatusText.className = "text-sm text-yellow-500 mb-1";
                } else if (msg === "Penuh") {
                    tankStatusText.className = "text-sm text-green-500 mb-1";
                }
            }

            if (topic === "rainwater/pompa/status") {
                const statusElement = document.getElementById("pump-status");
                const pumpVisual = document.getElementById("pump-visual");
                const pumpText = document.getElementById("pump-indicator-text");
                const pumpAction = document.getElementById("pump-action");

                statusElement.textContent = msg;

                if (msg === "ON") {
                    statusElement.className = "text-3xl font-bold mt-2 text-green-500";
                    pumpVisual.className = "w-8 h-8 mx-auto mt-2 rounded-full bg-green-500 animate-pulse";
                    pumpText.textContent = "Pompa: Aktif";
                    pumpText.className = "text-sm text-green-500";
                    pumpAction.textContent = "Menyiram tanaman sekarang";
                    pumpAction.className = "text-sm text-green-500 mt-1";
                } else {
                    statusElement.className = "text-3xl font-bold mt-2 text-red-500";
                    pumpVisual.className = "w-8 h-8 mx-auto mt-2 rounded-full bg-red-500";
                    pumpText.textContent = "Pompa: Nonaktif";
                    pumpText.className = "text-sm text-red-500";
                    pumpAction.textContent = "Standby";
                    pumpAction.className = "text-sm text-gray-500 mt-1";
                }
            }

            if (topic === "rainwater/serial_number/5678") {
                const statusElement = document.getElementById("rainwater/serial_number/5678");
                const statusDot = statusElement.querySelector('span');
                statusElement.textContent = msg;
                if (msg === "Online") {
                    statusDot.className = "w-2 h-2 mr-1 rounded-full bg-green-500";
                    statusElement.className =
                        "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800";
                } else {
                    statusDot.className = "w-2 h-2 mr-1 rounded-full bg-red-500";
                    statusElement.className =
                        "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800";
                }
            }
        });

        @auth
        document.getElementById("btn-on")?.addEventListener("click", () => {
            client.publish("rainwater/pompa/set", "ON", {
                qos: 1
            });
        });

        document.getElementById("btn-off")?.addEventListener("click", () => {
            client.publish("rainwater/pompa/set", "OFF", {
                qos: 1
            });
        });

        document.getElementById("btn-auto")?.addEventListener("click", () => {
            client.publish("rainwater/pompa/auto", "", {
                qos: 1
            });
        });
        @endauth
    </script>
</body>

</html>
