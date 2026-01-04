const menuBtn = document.getElementById("menu-btn");
const sidebar = document.getElementById("sidebar");
const mainContent = document.getElementById("main-content");

// Toggle sidebar open/close
menuBtn.addEventListener("click", (e) => {
  e.stopPropagation(); // prevent body click trigger
  sidebar.classList.toggle("show");
  sidebar.classList.toggle("hidden");
  mainContent.classList.toggle("expanded");
});

// Close sidebar when clicking outside (mobile view only)
document.addEventListener("click", (e) => {
  const isClickInsideSidebar = sidebar.contains(e.target);
  const isClickOnMenuBtn = menuBtn.contains(e.target);

  if (!isClickInsideSidebar && !isClickOnMenuBtn && window.innerWidth <= 992) {
    sidebar.classList.add("hidden");
    sidebar.classList.remove("show");
    mainContent.classList.remove("expanded");
  }
});

// Also close sidebar when any nav link is clicked (for mobile)
sidebar.querySelectorAll("a").forEach(link => {
  link.addEventListener("click", () => {
    if (window.innerWidth <= 992) {
      sidebar.classList.add("hidden");
      sidebar.classList.remove("show");
      mainContent.classList.remove("expanded");
    }
  });
});

// Chart.js graph
const ctx = document.getElementById('chart').getContext('2d');
new Chart(ctx, {
  type: 'line',
  data: {
    labels: ['Mar 8', 'Mar 18', 'Mar 29', 'Apr 8'],
    datasets: [{
      label: 'Growth',
      data: [3200, 4200, 5538, 5200],
      borderColor: '#4a9eff',
      backgroundColor: 'rgba(74,158,255,0.2)',
      fill: true,
      tension: 0.4,
      pointRadius: 5,
      pointHoverRadius: 7
    }]
  },
  options: {
    plugins: { legend: { display: false } },
    scales: { 
      x: { ticks: { color: '#bbb' } }, 
      y: { ticks: { color: '#bbb' } } 
    }
  }
});








// Reset sidebar visibility when switching between mobile and desktop
window.addEventListener("resize", () => {
  if (window.innerWidth > 992) {
    sidebar.classList.remove("hidden");
    sidebar.classList.remove("show");
    mainContent.classList.remove("expanded");
  }
});
window.addEventListener("load", () => {
  if (window.innerWidth > 992) {
    sidebar.classList.remove("hidden");
    sidebar.classList.remove("show");
    mainContent.classList.remove("expanded");
  }
});




























