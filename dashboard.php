<?php

session_start();

if (!isset($_SESSION["student_id"])) {
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Student Dashboard - UCGS</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #050505;
            color: white;
            font-family: Arial, sans-serif;
            overflow-x: hidden;
        }

        .header {
            padding: 18px 50px;
            background: #0b0b0b;
            border-bottom: 1px solid #222;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #00e5ff;
        }

        .logout {
            color: #ff5555;
            text-decoration: none;
            border: 1px solid #ff5555;
            padding: 9px 18px;
            border-radius: 7px;
            transition: 0.3s;
            white-space: nowrap;
        }

        .logout:hover {
            background: #ff5555;
            color: black;
        }

        .welcome {
            padding: 45px 50px 25px;
        }

        .welcome h1 {
            color: #00e5ff;
            margin: 0 0 10px;
            font-size: 32px;
        }

        .welcome p {
            color: #aaa;
            margin: 0;
            line-height: 1.6;
        }

        .email {
            color: #00ff88;
            word-break: break-word;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            padding: 20px 50px;
        }

        .card {
            min-height: 230px;
            background: #111;
            border: 1px solid #222;
            border-radius: 15px;
            padding: 25px;
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            border-color: #00e5ff;
            box-shadow: 0 0 20px rgba(0, 229, 255, 0.15);
        }

        .icon {
            font-size: 35px;
            margin-bottom: 15px;
        }

        .card h2 {
            color: #00e5ff;
            font-size: 21px;
            margin: 0 0 12px;
        }

        .card p {
            color: #aaa;
            line-height: 1.5;
            margin: 0;
        }

        .button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 18px;
            border-radius: 7px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .submit {
            background: #00e5ff;
            color: black;
        }

        .complaints {
            background: #00ff88;
            color: black;
        }

        .button:hover {
            box-shadow: 0 0 15px rgba(0, 229, 255, 0.4);
        }

        .footer {
            margin-top: 60px;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #222;
            color: #666;
            font-size: 14px;
        }

        @media (max-width: 1100px) {

            .cards {
                grid-template-columns: repeat(2, 1fr);
            }

        }

        @media (max-width: 700px) {

            .header {
                padding: 16px 20px;
            }

            .logo {
                font-size: 24px;
            }

            .welcome {
                padding: 35px 20px 20px;
            }

            .welcome h1 {
                font-size: 27px;
            }

            .cards {
                grid-template-columns: 1fr;
                padding: 15px 20px;
                gap: 18px;
            }

            .card {
                min-height: auto;
                padding: 22px;
            }

            .button {
                width: 100%;
                text-align: center;
            }

            .footer {
                margin-top: 40px;
            }

        }

        @media (max-width: 400px) {

            .header {
                padding: 14px 15px;
            }

            .logo {
                font-size: 22px;
            }

            .logout {
                padding: 8px 13px;
                font-size: 14px;
            }

            .welcome {
                padding: 28px 15px 15px;
            }

            .welcome h1 {
                font-size: 24px;
            }

            .cards {
                padding: 12px 15px;
            }

            .card {
                padding: 20px;
            }

        }

    </style>

</head>

<body>

    <div class="header">

        <div class="logo">
            UCGS
        </div>

        <a href="logout.php" class="logout">
            Logout
        </a>

    </div>


    <div class="welcome">

        <h1>
            Welcome,
            <?php echo htmlspecialchars($_SESSION["student_name"]); ?>!
        </h1>

        <p>
            College Email:
            <span class="email">
                <?php echo htmlspecialchars($_SESSION["student_email"]); ?>
            </span>
        </p>

    </div>


    <div class="cards">

        <div class="card">

            <div class="icon">📝</div>

            <h2>
                Submit Complaint
            </h2>

            <p>
                Submit a new complaint or grievance
                to the concerned department.
            </p>

            <a href="submit_complaint.php" class="button submit">
                Submit Now
            </a>

        </div>


        <div class="card">

            <div class="icon">📋</div>

            <h2>
                My Complaints
            </h2>

            <p>
                View your submitted complaints and
                check their current status.
            </p>

            <a href="my_complaints.php" class="button complaints">
                View Complaints
            </a>

        </div>


        <div class="card">

            <div class="icon">🔍</div>

            <h2>
                Track Status
            </h2>

            <p>
                Track whether your complaint is
                Pending, In Progress or Resolved.
            </p>

        </div>


        <div class="card">

            <div class="icon">🔒</div>

            <h2>
                Secure Account
            </h2>

            <p>
                Your account is protected through
                secure login authentication.
            </p>

        </div>

    </div>


    <div class="footer">

        © 2026 UCGS | Complaint & Grievance System

    </div>

</body>

</html>