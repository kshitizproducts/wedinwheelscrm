<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <style>
        body{
            background:#0f0f0f;
            color:#fff;
        }
        .card-dark{
            background:#1e1e1e;
            border-radius:16px;
            border:0;
        }
        .section-title{
            font-weight:700;
            color:#ffc107;
        }
        .sub-text{
            color:#8c8c8c;
        }
    </style>

</head>

<body>

<div class="container-fluid p-4">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
