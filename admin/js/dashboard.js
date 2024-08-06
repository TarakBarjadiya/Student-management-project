// ---------------------------------clock-------------------------------
function updateClock() {
  const clockElement = document.getElementById('clock');
  const now = new Date();
  const hours = String(now.getHours()).padStart(2, '0');
  const minutes = String(now.getMinutes()).padStart(2, '0');
  const seconds = String(now.getSeconds()).padStart(2, '0');
  const currentTime = `${hours}:${minutes}:${seconds}`;
  clockElement.textContent = currentTime;
}

// Update the clock every second
setInterval(updateClock, 1000);

// Initialize the clock
updateClock();