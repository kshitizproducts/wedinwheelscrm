<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WedinWheels | Login</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  
  <style>
    body {
      margin: 0;
      font-family: "Segoe UI", sans-serif;
      background: url("https://wedinwheels.com/wp-content/uploads/2024/01/2.jpg") no-repeat center center/cover;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
      color: #fff;
    }
    body::after {
      content: "";
      position: absolute; inset: 0;
      background: rgba(0,0,0,0.8);
      backdrop-filter: blur(4px);
    }

    .container {
      position: relative;
      z-index: 2;
      width: 90%;
      max-width: 1000px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 50px;
    }

    /* Left side - logo + tagline */
    .left {
      flex: 1;
    }
    .left img {
      width: 260px;
      margin-bottom: 20px;
    }
    .left p {
      font-size: 20px;
      font-weight: 400;
      max-width: 400px;
      line-height: 1.5;
      color: #f3f3f3;
    }

    /* Right side - login box */
    .login-container {
      flex: 1;
      background: rgba(255,255,255,0.05);
      padding: 40px 30px;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.6);
      text-align: center;
      backdrop-filter: blur(12px);
    }

    .login-container h2 {
      margin-bottom: 25px;
      font-size: 28px;
      color: #fbbf24;
      letter-spacing: 1px;
    }

    /* Password Wrapper Style */
    .password-wrapper {
      position: relative;
      width: 100%;
    }

    .login-container input {
      width: 100%;
      padding: 14px 16px;
      margin-bottom: 15px;
      border-radius: 10px;
      border: 1px solid #333;
      background: rgba(30,41,59,0.85);
      color: #fff;
      font-size: 16px;
      outline: none;
      transition: all 0.3s;
      box-sizing: border-box;
    }

    .login-container input:focus {
      border-color: #f59e0b;
      box-shadow: 0 0 8px #f59e0b66;
    }

    /* Eye Icon Positioning */
    .toggle-password {
      position: absolute;
      right: 15px;
      top: 15px; /* input padding aur alignment ke hisab se */
      cursor: pointer;
      color: #aaa;
      transition: 0.3s;
      z-index: 10;
    }

    .toggle-password:hover {
      color: #fbbf24;
    }

    .login-container button {
      margin-top: 20px;
      width: 100%;
      padding: 14px;
      font-size: 17px;
      font-weight: 600;
      border: none;
      border-radius: 50px;
      cursor: pointer;
      background: linear-gradient(45deg, #f59e0b, #fbbf24);
      color: #111;
      transition: 0.3s;
    }
    .login-container button:hover {
      background: linear-gradient(45deg, #facc15, #f59e0b);
      transform: scale(1.05);
      box-shadow: 0 5px 20px rgba(245,158,11,0.4);
    }

    .login-container .options {
      margin-top: 20px;
      font-size: 14px;
      color: #ddd;
    }
    .login-container .options a {
      color: #ff4d4d;
      font-weight: 600;
      margin-left: 5px;
      text-decoration: none;
    }
    .login-container .options a:hover {
      color: #fbbf24;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .container {
        flex-direction: column;
        text-align: center;
      }
      .left {
        margin-bottom: 30px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="left">
      <img src="https://wedinwheels.com/wp-content/uploads/2024/01/wedinwheels-logo.png" alt="WedinWheels Logo">
      <p>WedinWheels helps you find luxury cars for weddings and events with ease.</p>
    </div>

    <div class="login-container">
      <h2>Login to WedinWheels</h2>
      <form method="post" action="{{ url('login_now') }}">
        @csrf
        <input type="email" name="email" placeholder="Email Address" value="admin@gmail.com" required>
        
        <div class="password-wrapper">
          <input type="password" name="password" id="password" placeholder="Password" value="password" required>
          <i class="fa-solid fa-eye toggle-password" id="eyeIcon"></i>
        </div>

        <button type="submit">Login</button>
      </form>
      <div class="options">
        <p>Forgot Password? <a href="#">Click here</a></p>
        <p>Don't have an account? <a href="#">Register</a></p>
      </div>
    </div>
  </div>

  <script>
    const passwordField = document.querySelector('#password');
    const eyeIcon = document.querySelector('#eyeIcon');

    eyeIcon.addEventListener('click', function () {
      // Toggle the type attribute
      const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordField.setAttribute('type', type);
      
      // Toggle the eye icon
      this.classList.toggle('fa-eye');
      this.classList.toggle('fa-eye-slash');
    });
  </script>
</body>
</html>