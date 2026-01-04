<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Live Alert (Raw DB)</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://js.pusher.com/8.2/pusher.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h3>⚡ Laravel 12 Live Alert (Raw Query)</h3>
        <form id="alertForm">
            <div class="mb-3">
                <input type="text" name="content" id="content" class="form-control" placeholder="Enter alert content" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Alert</button>
        </form>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="alertLabel">🚨 New Alert!</h5>
          </div>
          <div class="modal-body" id="alertMessage"></div>
        </div>
      </div>
    </div>

    <script>
        // Submit form to add new alert
        document.getElementById("alertForm").addEventListener("submit", function(e) {
            e.preventDefault();
            fetch("{{ route('alert.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ content: document.getElementById("content").value })
            })
            .then(res => res.json())
            .then(data => console.log("Inserted:", data));
        });

        // Listen for Pusher event
        Pusher.logToConsole = true;

        var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}'
        });

        var channel = pusher.subscribe('alert-channel');
        channel.bind('alert.created', function(data) {
            console.log("Received event:", data); // ✅ Debug line
            document.getElementById("alertMessage").innerText = data.alert.content;
            var myModal = new bootstrap.Modal(document.getElementById('alertModal'));
            myModal.show();
        });
    </script>
</body>
</html>
