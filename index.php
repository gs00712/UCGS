<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>UCGS - Complaint & Grievance System</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: #050505;
            color: white;
            font-family: Arial, sans-serif;
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* NAVBAR */

        .navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(5, 5, 5, 0.97);
            border-bottom: 1px solid #222;
            padding: 18px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #00e5ff;
            white-space: nowrap;
        }

        .logo span {
            color: #00ff88;
        }

        .nav-links {
            display: flex;
            gap: 25px;
            align-items: center;
        }

        .nav-links a {
            color: #ccc;
            text-decoration: none;
            transition: 0.3s;
            white-space: nowrap;
        }

        .nav-links a:hover {
            color: #00e5ff;
        }

        .nav-login {
            border: 1px solid #00e5ff;
            padding: 9px 17px;
            border-radius: 7px;
        }


        /* HERO */

        .hero {
            min-height: 600px;
            padding: 100px 7%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 50px;
        }

        .hero-content {
            max-width: 700px;
            min-width: 0;
        }

        .badge {
            display: inline-block;
            padding: 8px 15px;
            border: 1px solid #00ff88;
            color: #00ff88;
            border-radius: 30px;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .hero h1 {
            font-size: 58px;
            line-height: 1.1;
            margin-bottom: 20px;
        }

        .hero h1 span {
            color: #00e5ff;
        }

        .hero p {
            color: #aaa;
            font-size: 18px;
            max-width: 620px;
            margin-bottom: 35px;
        }

        .hero-buttons {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 14px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            transition: 0.3s;
            text-align: center;
        }

        .btn-primary {
            background: #00e5ff;
            color: #000;
        }

        .btn-primary:hover {
            background: #00ff88;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 255, 136, 0.2);
        }

        .btn-outline {
            border: 1px solid #444;
            color: white;
        }

        .btn-outline:hover {
            border-color: #00e5ff;
            color: #00e5ff;
            transform: translateY(-3px);
        }


        /* HERO VISUAL */

        .hero-box {
            width: 360px;
            max-width: 100%;
            flex-shrink: 0;
            background: #0d0d0d;
            border: 1px solid #222;
            border-radius: 20px;
            padding: 30px;
        }

        .hero-box h3 {
            color: #00e5ff;
            margin-bottom: 20px;
        }

        .status {
            background: #151515;
            padding: 15px;
            margin: 12px 0;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .pending {
            color: #ffd166;
        }

        .progress {
            color: #00e5ff;
        }

        .resolved {
            color: #00ff88;
        }


        /* SECTION */

        .section {
            padding: 80px 7%;
        }

        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title h2 {
            font-size: 36px;
            margin-bottom: 10px;
        }

        .section-title span {
            color: #00e5ff;
        }

        .section-title p {
            color: #888;
        }


        /* FEATURES */

        .features {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .card {
            background: #0d0d0d;
            border: 1px solid #222;
            border-radius: 15px;
            padding: 30px;
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-7px);
            border-color: #00e5ff;
        }

        .icon {
            font-size: 35px;
            margin-bottom: 18px;
        }

        .card h3 {
            color: #00e5ff;
            margin-bottom: 10px;
        }

        .card p {
            color: #999;
        }


        /* PROCESS */

        .process {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        .step {
            text-align: center;
            padding: 35px;
            background: #0d0d0d;
            border: 1px solid #222;
            border-radius: 15px;
        }

        .number {
            width: 55px;
            height: 55px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: #00e5ff;
            color: #000;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 22px;
            font-weight: bold;
        }

        .step h3 {
            color: #00ff88;
            margin-bottom: 10px;
        }

        .step p {
            color: #999;
        }


        /* CTA */

        .cta {
            margin: 40px 7% 80px;
            padding: 60px 30px;
            text-align: center;
            background: #0d0d0d;
            border: 1px solid #222;
            border-radius: 20px;
        }

        .cta h2 {
            font-size: 35px;
            margin-bottom: 15px;
        }

        .cta p {
            color: #999;
            margin-bottom: 25px;
        }


        /* FOOTER */

        footer {
            border-top: 1px solid #222;
            padding: 30px 7%;
            text-align: center;
            color: #666;
        }

        footer strong {
            color: #00e5ff;
        }


        /* TABLET */

        @media (max-width: 1000px) {

            .hero {
                flex-direction: column;
                text-align: center;
                padding-top: 70px;
            }

            .hero-content {
                max-width: 800px;
            }

            .hero p {
                margin-left: auto;
                margin-right: auto;
            }

            .hero-buttons {
                justify-content: center;
            }

            .hero-box {
                width: 100%;
                max-width: 500px;
            }

            .features {
                grid-template-columns: repeat(2, 1fr);
            }

            .process {
                grid-template-columns: 1fr;
                max-width: 650px;
                margin: auto;
            }

            .nav-links a:not(.nav-login) {
                display: none;
            }

        }


        /* MOBILE */

        @media (max-width: 600px) {

            .navbar {
                padding: 14px 5%;
            }

            .logo {
                font-size: 24px;
            }

            .nav-links {
                gap: 0;
            }

            .nav-login {
                padding: 8px 12px;
                font-size: 13px;
            }

            .hero {
                min-height: auto;
                padding: 55px 5% 60px;
                gap: 40px;
            }

            .badge {
                font-size: 12px;
                padding: 7px 12px;
                margin-bottom: 20px;
            }

            .hero h1 {
                font-size: 38px;
                line-height: 1.12;
            }

            .hero p {
                font-size: 16px;
                margin-bottom: 28px;
            }

            .hero-buttons {
                flex-direction: column;
                width: 100%;
                gap: 12px;
            }

            .hero-buttons .btn,
            .hero-buttons a[style] {
                width: 100%;
                margin: 0 !important;
                padding: 14px 18px !important;
            }

            .hero-box {
                padding: 22px;
                border-radius: 15px;
            }

            .status {
                font-size: 14px;
                padding: 13px;
            }

            .section {
                padding: 60px 5%;
            }

            .section-title {
                margin-bottom: 35px;
            }

            .section-title h2 {
                font-size: 29px;
                line-height: 1.2;
            }

            .section-title p {
                font-size: 15px;
            }

            .features {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .card {
                padding: 24px;
            }

            .card:hover {
                transform: none;
            }

            .process {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .step {
                padding: 28px 22px;
            }

            .cta {
                margin: 20px 5% 55px;
                padding: 40px 20px;
                border-radius: 15px;
            }

            .cta h2 {
                font-size: 29px;
            }

            .cta p {
                font-size: 15px;
            }

            .cta .btn {
                width: 100%;
            }

            footer {
                padding: 25px 5%;
                font-size: 13px;
            }

        }


        /* VERY SMALL MOBILE */

        @media (max-width: 380px) {

            .logo {
                font-size: 22px;
            }

            .nav-login {
                font-size: 12px;
                padding: 7px 9px;
            }

            .hero h1 {
                font-size: 33px;
            }

            .hero-box {
                padding: 18px;
            }

            .status {
                flex-direction: column;
                gap: 3px;
            }

        }

    </style>

</head>

<body>


<!-- NAVBAR -->

<nav class="navbar">

    <div class="logo">
        UC<span>GS</span>
    </div>

    <div class="nav-links">

        <a href="#home">
            Home
        </a>

        <a href="#features">
            Features
        </a>

        <a href="#process">
            How It Works
        </a>

        <a
            href="student/login.php"
            class="nav-login"
        >
            Student Login
        </a>

    </div>

</nav>


<!-- HERO -->

<section class="hero" id="home">

    <div class="hero-content">

        <div class="badge">
            🎓 Digital Grievance Management Platform
        </div>

        <h1>
            Your Voice.
            <br>
            Your <span>Complaint.</span>
            <br>
            Our Responsibility.
        </h1>

        <p>
            UCGS provides students with a simple and transparent
            platform to submit complaints, track progress and
            receive updates from the concerned department.
        </p>

        <div class="hero-buttons">

            <a
                href="student/register.php"
                class="btn btn-primary"
            >
                Register as Student
            </a>

            <a
                href="student/login.php"
                class="btn btn-outline"
            >
                Student Login
            </a>

            <a
                href="department/login.php"
                class="btn btn-outline"
            >
                Department Login
            </a>

            <a
                href="admin/login.php"
                class="btn btn-outline"
            >
                Admin Login
            </a>

        </div>

    </div>


    <div class="hero-box">

        <h3>
            Complaint Status
        </h3>

        <div class="status">

            <span>
                Academic Issue
            </span>

            <span class="resolved">
                Resolved
            </span>

        </div>

        <div class="status">

            <span>
                Examination
            </span>

            <span class="progress">
                In Progress
            </span>

        </div>

        <div class="status">

            <span>
                Hostel Complaint
            </span>

            <span class="pending">
                Pending
            </span>

        </div>

        <p style="color:#777; margin-top:20px;">
            Track your complaints from one place.
        </p>

    </div>

</section>


<!-- FEATURES -->

<section class="section" id="features">

    <div class="section-title">

        <h2>
            Everything You Need in <span>One Place</span>
        </h2>

        <p>
            A structured system for transparent complaint management.
        </p>

    </div>


    <div class="features">

        <div class="card">

            <div class="icon">
                📝
            </div>

            <h3>
                Easy Complaint Submission
            </h3>

            <p>
                Submit your grievance online and select
                the department responsible for handling it.
            </p>

        </div>


        <div class="card">

            <div class="icon">
                🔍
            </div>

            <h3>
                Track Your Complaint
            </h3>

            <p>
                Check your complaint ID, current status
                and previous status updates.
            </p>

        </div>


        <div class="card">

            <div class="icon">
                🏢
            </div>

            <h3>
                Department Processing
            </h3>

            <p>
                Concerned departments can review complaints
                and update their progress.
            </p>

        </div>


        <div class="card">

            <div class="icon">
                🔒
            </div>

            <h3>
                Secure Access
            </h3>

            <p>
                Authentication-based access helps protect
                student and complaint information.
            </p>

        </div>

    </div>

</section>


<!-- HOW IT WORKS -->

<section class="section" id="process">

    <div class="section-title">

        <h2>
            How <span>UCGS</span> Works
        </h2>

        <p>
            Submit, track and resolve your grievance in three simple steps.
        </p>

    </div>


    <div class="process">

        <div class="step">

            <div class="number">
                1
            </div>

            <h3>
                Submit
            </h3>

            <p>
                Login using your college account and
                submit your complaint to the appropriate department.
            </p>

        </div>


        <div class="step">

            <div class="number">
                2
            </div>

            <h3>
                Track
            </h3>

            <p>
                Your complaint receives a unique ID and
                you can monitor its current status.
            </p>

        </div>


        <div class="step">

            <div class="number">
                3
            </div>

            <h3>
                Resolve
            </h3>

            <p>
                The concerned department reviews the complaint,
                takes action and updates the final status.
            </p>

        </div>

    </div>

</section>


<!-- CTA -->

<section class="cta">

    <h2>
        Have a Complaint?
    </h2>

    <p>
        Submit your grievance and let the concerned department
        take action.
    </p>

    <a
        href="student/register.php"
        class="btn btn-primary"
    >
        Get Started
    </a>

</section>


<!-- FOOTER -->

<footer>

    <p>
        © 2026
        <strong>UCGS</strong>
        - University/College Complaint & Grievance System
    </p>

</footer>


</body>

</html>