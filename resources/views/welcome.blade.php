<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coming Soon | Trishna Clinic</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #0056b3;
            --secondary-color: #28a745;
            --text-dark: #333;
            --text-light: #666;
            --white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: var(--text-dark);
            text-align: center;
            padding: 20px;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            padding: 50px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
            border-top: 8px solid var(--primary-color);
        }

        .logo {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .logo i {
            color: var(--secondary-color);
            margin-right: 10px;
        }

        h1 {
            font-size: 3rem;
            margin-bottom: 20px;
            color: #222;
        }

        p {
            font-size: 1.1rem;
            color: var(--text-light);
            margin-bottom: 30px;
            line-height: 1.6;
        }

        /* Countdown Timer */
        .countdown {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 40px;
        }

        .time-box {
            background: var(--primary-color);
            color: white;
            padding: 15px;
            border-radius: 10px;
            min-width: 80px;
        }

        .time-box span {
            display: block;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .time-box label {
            font-size: 0.8rem;
            text-transform: uppercase;
        }

        /* Subscription Form */
        .subscribe-form {
            display: flex;
            gap: 10px;
            max-width: 500px;
            margin: 0 auto 40px;
        }

        input[type="email"] {
            flex: 1;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 30px;
            outline: none;
            font-size: 1rem;
        }

        button {
            padding: 15px 30px;
            background: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }

        button:hover {
            background: #218838;
            transform: translateY(-2px);
        }

        /* Contact Info */
        .contact-info {
            border-top: 1px solid #eee;
            padding-top: 30px;
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
        }

        .info-item {
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .info-item i {
            color: var(--primary-color);
            margin-right: 5px;
        }

        .social-links {
            margin-top: 20px;
        }

        .social-links a {
            color: var(--primary-color);
            font-size: 1.5rem;
            margin: 0 10px;
            transition: 0.3s;
        }

        .social-links a:hover {
            color: var(--secondary-color);
        }

        @media (max-width: 600px) {
            h1 {
                font-size: 2rem;
            }

            .subscribe-form {
                flex-direction: column;
            }

            .countdown {
                gap: 10px;
            }

            .time-box {
                min-width: 65px;
                padding: 10px;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="logo">
            <img src="{{ url('assets/frontend/logo.png') }}" alt="Trishna Clinic Logo" style="width: 300px;">
        </div>

        <h1>Coming Soon</h1>
        <p>We are currently working hard to bring you a better healthcare experience.
            Our new website will feature online appointments, health tips, and digital prescriptions.</p>

        <div class="countdown">
            <div class="time-box">
                <span id="days">00</span>
                <label>Days</label>
            </div>
            <div class="time-box">
                <span id="hours">00</span>
                <label>Hours</label>
            </div>
            <div class="time-box">
                <span id="minutes">00</span>
                <label>Mins</label>
            </div>
            <div class="time-box">
                <span id="seconds">00</span>
                <label>Secs</label>
            </div>
        </div>

        <form class="subscribe-form">
            <input type="email" placeholder="Enter your email for updates" required>
            <button type="submit">Notify Me</button>
        </form>

        <div class="contact-info">
            <div class="info-item">
                <i class="fas fa-phone"></i> 01911 67 74 24
            </div>
            <div class="info-item">
                <i class="fas fa-envelope"></i> trishnaclinic@gmail.com
            </div>
            <div class="info-item">
                <i class="fas fa-map-marker-alt"></i> C/14, Road No #02, Nirala, Khulna, Bangladesh.
            </div>
        </div>

        <div class="social-links">
            <a href="https://www.facebook.com/trishnaclinic/"><i class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-whatsapp"></i></a>
        </div>
    </div>

    <script>
        // Set launch date (16 days from now)
        const launchDate = new Date();
        launchDate.setDate(launchDate.getDate() + 30);

        function updateCountdown() {
            const now = new Date().getTime();
            const diff = launchDate - now;

            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            document.getElementById('days').innerText = days < 10 ? '0' + days : days;
            document.getElementById('hours').innerText = hours < 10 ? '0' + hours : hours;
            document.getElementById('minutes').innerText = minutes < 10 ? '0' + minutes : minutes;
            document.getElementById('seconds').innerText = seconds < 10 ? '0' + seconds : seconds;
        }

        setInterval(updateCountdown, 1000);
        updateCountdown();
    </script>
</body>

</html>