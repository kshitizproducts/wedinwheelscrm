<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>WedinWheels Dashboard (Bootstrap)</title>

  <!-- Bootstrap 5 CSS -->
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
  <link href="{{asset('backend/bootstrap.min.css')}}" rel="stylesheet">
  <!-- FontAwesome -->
  <script src="https://kit.fontawesome.com/a2e0a4b2d1.js" crossorigin="anonymous"></script>
 <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> -->
  <link href="{{ asset('backend/css/bootstrap.min.css') }}" rel="stylesheet">
  <!-- Chart.js -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
    <script src="{{ asset('backend/js/chart.js') }}"></script>

  <style>
    body {
      background-color: #0e0e12;
      color: #eaeaea;
      font-family: 'Poppins', sans-serif;
    }

    /* Sidebar */
    .sidebar {
      background-color: #1c1c22; /* greyish dark tone */
      height: 100vh;
      width: 240px;
      position: fixed;
      top: 0;
      left: 0;
      padding: 1rem;
      overflow-y: auto;
      z-index: 1000;
      box-shadow: 2px 0 10px rgba(0,0,0,0.4);
    }

    .sidebar a {
      color: #cfcfcf;
      text-decoration: none;
      display: block;
      padding: 0.75rem 1rem;
      border-radius: 8px;
      margin-bottom: 0.3rem;
      transition: 0.3s;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background-color: #2f2f36;
      color: #fff;
    }

    /* Highlighted headings */
    .sidebar h4 {
      position: relative;
      font-size: 12px;
      text-transform: uppercase;
      color: #fbbf24;
      margin-top: 1.5rem;
      margin-bottom: 0.6rem;
      font-weight: 600;
      letter-spacing: 0.5px;
      padding-left: 10px;
    }

    .sidebar h4::before {
      content: "";
      position: absolute;
      left: 0;
      top: 50%;
      transform: translateY(-50%);
      width: 4px;
      height: 14px;
      background-color: #ffcc33;
      border-radius: 2px;
    }

    /* Main content */
    .main-content {
      margin-left: 240px;
      padding: 20px;
    }

    .topbar {
      background-color: #1f1f26;
      padding: 10px 20px;
      border-radius: 10px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .topbar input {
      background: #2b2b2f;
      border: none;
      color: #fff;
      border-radius: 8px;
      padding: 8px 12px;
      width: 100%;
      max-width: 300px;
    }

    /* Dashboard cards */
    .dashboard .card {
      background: rgba(255, 255, 255, 0.08);
      border: 1px solid rgba(255, 255, 255, 0.15);
      border-radius: 16px;
      padding: 20px;
      position: relative;
      overflow: hidden;
      color: #fff;
    }

    .card h5 {
      color: #fff;
      font-weight: 600;
    }

    .card p {
      color: #ddd;
    }

    .card img.bg-preview {
      position: absolute;
      bottom: 0;
      right: 0;
      width: 100%;
      opacity: 0.25;
      border-radius: 0 0 16px 16px;
    }

    table {
      color: #fff;
    }

    table tbody tr:nth-child(odd) {
      background-color: rgba(255, 255, 255, 0.05);
    }

    .btn-yellow {
      background-color: #ffcc33;
      color: #000;
      font-weight: 600;
    }

    .profile img {
      width: 35px;
      height: 35px;
      border-radius: 50%;
    }

    .profile span {
      margin-left: 10px;
      color: #fff;
      font-weight: 500;
    }

    .text-light {
      color: #f5f5f5 !important;
    }

    /* Sidebar toggle responsive */
    @media (max-width: 992px) {
      .sidebar {
        left: -240px;
        transition: left 0.3s ease;
      }
      .sidebar.show {
        left: 0;
      }
      .main-content {
        margin-left: 0;
      }
    }
  </style>
</head>

<body>

<div class="d-flex">