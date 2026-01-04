<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WedinWheels</title>
    <style>
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #0d0d0d;
            color: #fff;
            line-height: 1.6;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        /* Header */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background: rgba(0, 0, 0, 0.9);
            z-index: 1000;
        }

        .logo img {
            height: 50px;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        nav ul li a {
            padding: 8px 12px;
            border-radius: 5px;
            transition: 0.3s;
        }

        nav ul li a:hover {
            background: #ff4d4d;
        }

        /* Mobile menu */
        .menu-toggle {
            display: none;
            flex-direction: column;
            gap: 4px;
            cursor: pointer;
        }

        .menu-toggle div {
            width: 25px;
            height: 3px;
            background: #fff;
        }

        nav {
            transition: 0.3s;
        }

        nav.active {
            position: absolute;
            top: 70px;
            right: 30px;
            background: #111;
            padding: 20px;
            border-radius: 10px;
        }

        nav.active ul {
            flex-direction: column;
            gap: 15px;
        }

        /* Hero */
        .hero {
            height: 100vh;
            background: url("https://wedinwheels.com/wp-content/uploads/2024/01/2.jpg") no-repeat center center/cover;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 0 20px;
            position: relative;
        }

        .hero::after {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 10px;
            color: #ff4d4d;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        .btn-primary {
            padding: 12px 25px;
            background: #ff4d4d;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background: #e60000;
        }

        /* Sections */
        .section {
            padding: 80px 20px;
        }

        .section-title {
            font-size: 2rem;
            text-align: center;
            margin-bottom: 40px;
            color: #ff4d4d;
        }

        /* Booking form */
        .contact-form {
            max-width: 600px;
            margin: auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .contact-form input,
        .contact-form select {
            padding: 12px;
            border-radius: 8px;
            border: none;
            outline: none;
        }

        /* Cars gallery */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .feature-card {
            background: #1a1a1a;
            padding: 15px;
            border-radius: 12px;
            text-align: center;
            transition: 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-card img {
            width: 100%;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            nav {
                display: none;
            }

            nav.active {
                display: block;
            }

            .menu-toggle {
                display: flex;
            }
        }
    </style>
</head>

<body>

    <!-- Header -->
    <header>
        <div class="logo">
            <img src="https://wedinwheels.com/wp-content/uploads/2024/01/wedinwheels-logo.png" alt="WedinWheels Logo">
        </div>
        <nav id="navbar">
            <ul>
                <li><a href="#hero">Home</a></li>
                <li><a href="#booking">Book</a></li>
                <li><a href="#cars">Cars</a></li>
                <li><a href="#pricing">Pricing</a></li>
                <li><a href="#contact">Contact</a></li>
                @if (Auth::check())
                    <li><a href="{{ url('dashboard') }}">Dashboard</a></li>
                @else
                    <li><a href="{{ url('login') }}">Login</a></li>
                @endif

            </ul>
        </nav>
        <div class="menu-toggle" id="menu-toggle">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </header>

    <!-- Hero -->
    <section id="hero" class="hero">
        <div class="hero-content">
            <h1>Luxury Cars for Your Special Day</h1>
            <p>Book premium vintage & luxury cars for weddings, events and celebrations.</p>
            <a href="#booking"><button class="btn-primary">Book Now</button></a>
        </div>
    </section>

    <!-- Booking -->
    <section id="booking" class="section">
        <h2 class="section-title">Book Your Car</h2>
        <form class="contact-form">
            <input type="text" placeholder="Source Location" required>
            <input type="text" placeholder="Destination Location" required>
            <input type="date" required>
            <select required>
                <option value="">Select Car</option>
                <option>Vintage Rolls Royce</option>
                <option>Classic Mercedes</option>
                <option>Luxury Jaguar</option>
                <option>Open Jeep</option>
            </select>
            <button class="btn-primary" type="submit">Check Availability</button>
        </form>
    </section>

    <style>
        /* Section styling */
        .section {
            padding: 70px 20px;
            text-align: center;
            background: linear-gradient(135deg, #0a0a0a, #111827);
            /* dark stylish bg */
            color: #fff;
        }

        .section-title {
            font-size: 32px;
            margin-bottom: 40px;
            font-weight: 600;
            color: #fbbf24;
            /* golden accent */
            letter-spacing: 1px;
        }

        /* Grid form */
        .contact-form {
            display: grid;
            gap: 20px;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            max-width: 1200px;
            margin: auto;
            background: rgba(255, 255, 255, 0.05);
            /* glass effect */
            padding: 25px;
            border-radius: 16px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.6);
        }

        /* Inputs & selects */
        .contact-form input,
        .contact-form select {
            padding: 14px 18px;
            font-size: 16px;
            border-radius: 10px;
            border: 1px solid #333;
            outline: none;
            background: rgba(30, 41, 59, 0.85);
            color: #fff;
            transition: all 0.3s ease;
        }

        .contact-form input:focus,
        .contact-form select:focus {
            border-color: #f59e0b;
            background: rgba(30, 41, 59, 1);
            box-shadow: 0 0 8px #f59e0b55;
        }

        /* Button */
        .contact-form button {
            background: linear-gradient(45deg, #f59e0b, #fbbf24);
            border: none;
            padding: 14px 22px;
            font-size: 17px;
            font-weight: 600;
            border-radius: 50px;
            color: #111;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .contact-form button:hover {
            background: linear-gradient(45deg, #facc15, #f59e0b);
            transform: scale(1.05);
            box-shadow: 0 5px 20px rgba(245, 158, 11, 0.4);
        }

        /* Mobile adjustments */
        @media (max-width: 600px) {
            .contact-form {
                padding: 20px;
                gap: 15px;
            }

            .section-title {
                font-size: 26px;
            }
        }
    </style>

    <!-- Cars -->
    <section id="cars" class="section">
        <h2 class="section-title">Our Cars</h2>
        <div class="features-grid">
            <div class="feature-card">
                <img src="https://wedinwheels.com/wp-content/uploads/2024/01/2.jpg">
                <h3>Vintage Royal</h3>
                <p>Perfect for weddings & events.</p>
            </div>
            <div class="feature-card">
                <img src="https://wedinwheels.com/wp-content/uploads/2024/01/1.jpg">
                <h3>Classic Mercedes</h3>
                <p>Elegant and timeless look.</p>
            </div>
            <div class="feature-card">
                <img src="https://wedinwheels.com/wp-content/uploads/2025/01/wed-in.jpg">
                <h3>Luxury Jaguar</h3>
                <p>Premium comfort ride.</p>
            </div>
            <div class="feature-card">
                <img src="https://wedinwheels.com/wp-content/uploads/2024/01/9c.jpg">
                <h3>Open Jeep</h3>
                <p>Perfect for baraat entry.</p>
            </div>
        </div>
    </section>

    <!-- Contact -->
    <section id="contact" class="section">
        <h2 class="section-title">Contact Us</h2>
        <form class="contact-form">
            <input type="text" placeholder="Your Name" required>
            <input type="email" placeholder="Your Email" required>
            <input type="text" placeholder="Message" required>
            <button class="btn-primary" type="submit">Send Message</button>
        </form>
    </section>








    <!-- -------single single starts  -->


    <section class="car-slider">
        <div class="car-container">

            <!-- Left Arrow -->
            <button class="slider-btn prev"><i class="fa fa-chevron-left"></i></button>

            <!-- Car Content -->
            <div class="car-content">
                <h3 id="carSliderBrand">TOYOTA</h3>
                <h1 id="carSliderName">CAMREY SE 350</h1>

                <div class="car-image-box">
                    <img id="carSliderImage" src="https://wedinwheels.com/wp-content/uploads/2024/01/2.jpg"
                        alt="Car">
                    <div class="price-tag">
                        <span>Starts From</span>
                        <h4 id="carSliderPrice">$799 <small>/day</small></h4>
                    </div>
                </div>

                <div class="features">
                    <span><i class="fa fa-cog"></i> Auto</span>
                    <span><i class="fa fa-bolt"></i> Power</span>
                    <span><i class="fa fa-tachometer"></i> 80K</span>
                    <span><i class="fa fa-snowflake"></i> AC</span>
                    <span><i class="fa fa-gas-pump"></i> Petrol</span>
                    <span><i class="fa fa-users"></i> 6 Persons</span>
                </div>

                <button class="rent-btn">Rent Now</button>

                <!-- Indicator Dots -->
                <div class="indicators" id="carSliderIndicators"></div>
            </div>

            <!-- Right Arrow -->
            <button class="slider-btn next"><i class="fa fa-chevron-right"></i></button>
        </div>
    </section>

    <style>
        .car-slider {
            background: #000;
            /* Pure black theme */
            text-align: center;
            padding: 60px 20px;
            position: relative;
            color: #fff;
        }

        .car-container {
            position: relative;
            max-width: 1200px;
            margin: auto;
        }

        .slider-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.15);
            border: none;
            border-radius: 50%;
            font-size: 20px;
            width: 50px;
            height: 50px;
            cursor: pointer;
            transition: 0.3s;
            color: #fff;
            z-index: 10;
            /* always on top */
        }

        .slider-btn:hover {
            background: #f59e0b;
            color: #fff;
        }

        .prev {
            left: -70px;
        }

        .next {
            right: -70px;
        }

        @media (max-width: 768px) {
            .prev {
                left: 10px;
            }

            .next {
                right: 10px;
            }

            .slider-btn {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
        }

        .car-content h3 {
            font-size: 16px;
            color: #9ca3af;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }

        .car-content h1 {
            font-size: 40px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #facc15;
        }

        .car-image-box {
            position: relative;
        }

        .car-image-box img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
        }

        .price-tag {
            position: absolute;
            bottom: -30px;
            left: 50%;
            transform: translateX(-50%);
            background: #0d9488;
            color: #fff;
            padding: 18px 28px;
            border-radius: 50%;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.25);
            text-align: center;
        }

        .price-tag h4 {
            margin: 0;
            font-size: 18px;
        }

        .price-tag small {
            font-size: 12px;
        }

        .features {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin: 60px 0 30px;
            flex-wrap: wrap;
        }

        .features span {
            background: #1f2937;
            color: #fff;
            padding: 10px 15px;
            border-radius: 30px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .rent-btn {
            background: #f59e0b;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        .rent-btn:hover {
            background: #d97706;
        }

        .indicators {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 25px;
        }

        .indicators span {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #475569;
            display: inline-block;
            cursor: pointer;
            transition: 0.3s;
        }

        .indicators span.active {
            background: #f59e0b;
            transform: scale(1.2);
        }
    </style>

    <script src="https://kit.fontawesome.com/a2e0a4b2d1.js" crossorigin="anonymous"></script>
    <script>
        const carSliderCars = [{
                brand: "TOYOTA",
                name: "CAMREY SE 350",
                img: "https://wedinwheels.com/wp-content/uploads/2024/01/2.jpg",
                price: "$799"
            },
            {
                brand: "BMW",
                name: "X5 LUXURY SUV",
                img: "https://wedinwheels.com/wp-content/uploads/2024/01/1.jpg",
                price: "$999"
            },
            {
                brand: "MERCEDES",
                name: "S-CLASS PREMIUM",
                img: "https://wedinwheels.com/wp-content/uploads/2025/01/wed-in.jpg",
                price: "$1299"
            },
            {
                brand: "AUDI",
                name: "A6 SPORTBACK",
                img: "https://wedinwheels.com/wp-content/uploads/2024/01/9c.jpg",
                price: "$899"
            }
        ];

        let carSliderCurrent = 0;
        const carSliderIndicators = document.getElementById("carSliderIndicators");

        // Render indicator dots
        function renderCarSliderIndicators() {
            carSliderIndicators.innerHTML = "";
            carSliderCars.forEach((_, i) => {
                const dot = document.createElement("span");
                dot.classList.toggle("active", i === carSliderCurrent);
                dot.onclick = () => goToCarSliderSlide(i);
                carSliderIndicators.appendChild(dot);
            });
        }

        // Change slide
        function goToCarSliderSlide(index) {
            carSliderCurrent = index;
            const car = carSliderCars[carSliderCurrent];
            document.getElementById("carSliderBrand").innerText = car.brand;
            document.getElementById("carSliderName").innerText = car.name;
            document.getElementById("carSliderImage").src = car.img;
            document.getElementById("carSliderPrice").innerHTML = car.price + " <small>/day</small>";
            renderCarSliderIndicators();
        }

        // Arrows
        document.querySelector(".next").addEventListener("click", () => {
            goToCarSliderSlide((carSliderCurrent + 1) % carSliderCars.length);
        });
        document.querySelector(".prev").addEventListener("click", () => {
            goToCarSliderSlide((carSliderCurrent - 1 + carSliderCars.length) % carSliderCars.length);
        });

        // Init
        renderCarSliderIndicators();
    </script>



    <!-- ----------end of single single -->












    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <!-- Ranchi Office -->
            <div class="footer-col">
                <h3>Ranchi Office:</h3>
                <p>
                    Address: Foto Me Plot No. HI/206, Harmu Housing Colony<br>
                    Near Sahjanand Chowk, P.S – Argora Ranchi-834002, Jharkhand
                </p>
            </div>

            <!-- Bokaro Office -->
            {{-- <div class="footer-col">
        <h3>Bokaro Office:</h3>
        <p>
          Address: Foto Me GC-6, City Center, Sec-4,<br>
          Bokaro Steel City-827004, Jharkhand, India
        </p>
      </div> --}}


            <div class="footer-col">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3662.8197307968835!2d85.30354517604319!3d23.358544703705757!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39f4e056341ba965%3A0x84439801a2482bc5!2sFoto%20Me%20Studio!5e0!3m2!1sen!2sin!4v1762068862861!5m2!1sen!2sin"
                    width="300" height="190" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>

            <!-- Contact Details -->
            <div class="footer-col">
                <h3>Contact Details:</h3>
                <p><i class="fa fa-phone"></i> +91 930 471 6076</p>
                <p><i class="fa fa-whatsapp"></i> +91 930 471 6076</p>
                <p><i class="fa fa-envelope"></i> wedinwheels@gmail.com</p>

                <div class="footer-socials">
                    <a href="#"><i class="fa fa-facebook"></i></a>
                    <a href="#"><i class="fa fa-instagram"></i></a>
                    <a href="#"><i class="fa fa-pinterest"></i></a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>2025 © Wed-In-Wheels | All Rights Reserved | Powered by <a href="https://kshitizkumar.com/">Kshitiz</a>
            </p>
        </div>
    </footer>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a2e0a4b2d1.js" crossorigin="anonymous"></script>

    <style>
        /* Footer Base */
        .footer {
            background: #0f172a;
            /* dark black theme */
            color: #cbd5e1;
            padding: 50px 20px 20px;
            font-family: "Segoe UI", sans-serif;
        }

        /* Footer container grid */
        .footer-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: auto;
        }

        /* Footer column */
        .footer-col h3 {
            font-size: 20px;
            margin-bottom: 15px;
            color: #fbbf24;
        }

        .footer-col p {
            font-size: 15px;
            line-height: 1.6;
            margin: 6px 0;
        }

        /* Social icons */
        .footer-socials {
            margin-top: 15px;
        }

        .footer-socials a {
            display: inline-block;
            margin-right: 12px;
            font-size: 18px;
            color: #fbbf24;
            background: rgba(255, 255, 255, 0.08);
            padding: 10px;
            border-radius: 50%;
            transition: 0.3s;
        }

        .footer-socials a:hover {
            background: #fbbf24;
            color: #0f172a;
        }

        /* Footer bottom */
        .footer-bottom {
            text-align: center;
            margin-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 15px;
            font-size: 14px;
            color: #9ca3af;
        }
    </style>

    <!-- Toggle Script -->
    <script>
        const toggle = document.getElementById("menu-toggle");
        const nav = document.getElementById("navbar");

        toggle.addEventListener("click", () => {
            nav.classList.toggle("active");
        });
    </script>
</body>

</html>
