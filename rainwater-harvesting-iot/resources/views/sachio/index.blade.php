<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Smart Aquarium</title>
    <link rel="stylesheet" href="assets/style.css">

</head>

<body>
    <!-- Video background -->
    <video autoplay muted loop id="bg-video">
        <source src="assets/videos/AquariumVideo1.mp4" type="video/mp4">

    </video>

    <h2>Status Aquarium</h2>
    <p>Suhu Air: <span id="suhu">--</span> °C</p>
    <p>Cahaya: <span id="lux">--</span> Lux</p>

    <h3>Kontrol Manual</h3>
    <button onclick="beriMakan()">Beri Makan</button>
    <audio id="makanSound" src="assets/sounds/bubble.mp3"></audio>
    <audio id="bubbleSound" src="assets/sounds/makan.mp3"></audio>


    <script src="assets/script.js"></script>

</body>

</html>
