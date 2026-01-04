
<!-- Bootstrap JS -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
 <script src="{{ asset('backend/js/bootstrap.bundle.min.js') }}"></script>

<!-- Sidebar Toggle -->
<script>
  const menuBtn = document.getElementById("menu-btn");
  const sidebar = document.getElementById("sidebar");

  menuBtn.addEventListener("click", () => {
    sidebar.classList.toggle("show");
  });

  document.addEventListener("click", function (e) {
    if (!sidebar.contains(e.target) && !menuBtn.contains(e.target) && window.innerWidth < 992) {
      sidebar.classList.remove("show");
    }
  });
</script>

<!-- Chart -->
<script>
  const ctx = document.getElementById('chart').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Mar 8', 'Mar 18', 'Mar 29', 'Apr 8'],
      datasets: [{
        label: 'Growth',
        data: [3200, 4200, 5538, 5200],
        borderColor: '#4a9eff',
        backgroundColor: 'rgba(74,158,255,0.3)',
        fill: true,
        tension: 0.4,
        pointRadius: 5,
        pointHoverRadius: 7
      }]
    },
    options: {
      plugins: { legend: { display: false } },
      scales: {
        x: { ticks: { color: '#eee' }, grid: { color: 'rgba(255,255,255,0.1)' } },
        y: { ticks: { color: '#eee' }, grid: { color: 'rgba(255,255,255,0.1)' } }
      }
    }
  });
</script>

<script src="{{asset('backend/js/sweetalert2.min.js')}}"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->

<!-- From Google Hosted Libraries -->
 <script src="{{asset('backend/js/jquery.min.js')}}"></script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> -->

<!-- From jQuery’s official CDN -->
<script src="{{asset('backend/js/jquery-3.7.1.min.js')}}"></script>

<!-- From jsDelivr -->
<!-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script> -->

</body>
</html>

<!-- pusher code start -->
<!-- <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
<script>
Pusher.logToConsole = true;

var pusher = new Pusher('f97abf787f3e576e05ac', {
  cluster: 'ap2'
});

var channel = pusher.subscribe('my-channel');
channel.bind('my-event', function(data) {
  alert('📢 New Notification: ' + data.title);
});
</script> -->
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Pusher CDN -->
<script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>

<script>
Pusher.logToConsole = true;

var pusher = new Pusher('f97abf787f3e576e05ac', {
  cluster: 'ap2'
});

var channel = pusher.subscribe('my-channel');

// Bind to the Laravel event
channel.bind('my-event', function(data) {

  // Show notification popup using SweetAlert2
  Swal.fire({
    title: '📢 New Notification',
    html: `
      <strong>${data.author}</strong><br>
      <p>${data.title}</p>
    `,
    icon: 'info',
    showCancelButton: true,
    confirmButtonText: 'Mark as Read ✅',
    cancelButtonText: 'Close',
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    allowOutsideClick: false
  }).then((result) => {
    if (result.isConfirmed) {
      // ✅ Show confirmation message
      Swal.fire({
        title: 'Notification Acknowledged',
        text: 'You have confirmed the notification.',
        icon: 'success',
        timer: 2000,
        showConfirmButton: false
      });
    }
  });
});
</script>


<!-- end of pusher code  -->
