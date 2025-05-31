const firebaseURL = "https://smartaquarium-65c36-default-rtdb.firebaseio.com";
// Ambil data sensor
setInterval(() => {
  fetch(`${firebaseURL}/sensor.json`)
    .then(res => res.json())
    .then(data => {
      document.getElementById("suhu").innerText = data.suhu;
      document.getElementById("lux").innerText = data.lux;
    });
}, 3000);

// Kirim perintah beri makan
function beriMakan() {
  fetch(`${firebaseURL}/kontrol/pakan.json`, {
    method: "PUT",
    body: "true"
  });

  const jumlahPelet = 5;
  const makanSound = document.getElementById('makanSound');
  const bubbleSound = document.getElementById('bubbleSound');

  for (let i = 0; i < jumlahPelet; i++) {
    const pelet = document.createElement("div");
    pelet.classList.add("pelet");

    const bubble = document.createElement("div");
    bubble.classList.add("bubble");

    const leftPos = Math.random() * 80 + 10; // posisi 10% - 90%
    pelet.style.left = `${leftPos}%`;
    bubble.style.left = `${leftPos}%`;

    document.body.appendChild(pelet);
    document.body.appendChild(bubble);
    bubbleSound.cloneNode(true).play();

    const fishes = document.querySelectorAll('.fish, .fish1, .fish2, .fish3, .fish4');
    let closestFish = null;
    let minDistance = Infinity;

    fishes.forEach(fish => {
      const fishRect = fish.getBoundingClientRect();
      const fishCenter = fishRect.left + fishRect.width / 2;
      const distance = Math.abs(fishCenter - (window.innerWidth * leftPos / 100));
      if (distance < minDistance) {
        minDistance = distance;
        closestFish = fish;
      }
    });

if (closestFish) {
  // Hentikan animasi sementara
  closestFish.style.animation = 'none';

  // Simpan posisi awal
  const originalTop = closestFish.style.top;
  const originalLeft = closestFish.style.left;

  // Hitung posisi target pelet
  const peletX = window.innerWidth * leftPos / 100;
  const peletY = window.innerHeight * 0.85;

  // Gerakkan ikan ke pelet
  closestFish.style.left = `${peletX - 60}px`;
  closestFish.style.top = `${peletY - 60}px`;
  closestFish.style.transform = 'scale(1.3)';
  makanSound.cloneNode(true).play();

  // Setelah 2 detik, kembali ke posisi awal dan hidupkan animasi lagi
  setTimeout(() => {
    closestFish.style.left = originalLeft;
    closestFish.style.top = originalTop;
    closestFish.style.transform = 'scale(1)';
    closestFish.style.animation = 'linearSwim 20s linear infinite';
  }, 2000);

    }
  }
}
