<?php

require_once __DIR__ . "/../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (!str_ends_with($email, "@tmu.ac.in")) {

        $message = "Please use your college email address (@tmu.ac.in).";

    } else {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO students (name, email, password)
                VALUES (?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "sss",
            $name,
            $email,
            $hashed_password
        );

        if ($stmt->execute()) {

            $message = "Registration successful! You can now login.";

        } else {

            $message = "Email already registered or registration failed.";

        }

        $stmt->close();
    }
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

    <title>Student Registration - UCGS</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            padding: 25px 15px;
            background: #050505;
            color: white;
            font-family: Arial, sans-serif;

            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            width: 100%;
            max-width: 430px;
            background: #111;
            padding: 35px;
            border-radius: 15px;
            border: 1px solid #222;
            box-shadow: 0 0 25px rgba(0, 229, 255, 0.15);
        }

        h1 {
            text-align: center;
            color: #00e5ff;
            margin: 0;
            font-size: 30px;
        }

        .subtitle {
            text-align: center;
            color: #aaa;
            margin: 10px 0 30px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #00ff88;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 14px;
            margin-bottom: 20px;
            background: #080808;
            color: white;
            border: 1px solid #333;
            border-radius: 8px;
            outline: none;
            font-size: 16px;
        }

        input:focus {
            border-color: #00e5ff;
            box-shadow: 0 0 8px rgba(0, 229, 255, 0.3);
        }

        input::placeholder {
            color: #666;
        }

        button {
            width: 100%;
            padding: 14px;
            background: #00e5ff;
            color: #000;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #00ff88;
            box-shadow: 0 0 15px rgba(0, 255, 136, 0.4);
        }

        .message {
            text-align: center;
            color: #00ff88;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .login {
            text-align: center;
            margin-top: 25px;
            color: #aaa;
            line-height: 1.6;
        }

        .login a {
            color: #00e5ff;
            text-decoration: none;
            font-weight: bold;
        }

        .login a:hover {
            color: #00ff88;
        }

        .back {
            text-align: center;
            margin-top: 20px;
        }

        .back a {
            color: #00ff88;
            text-decoration: none;
        }

        .back a:hover {
            color: #00e5ff;
        }

        @media (max-width: 500px) {

            body {
                padding: 15px 10px;
            }

            .container {
                padding: 27px 19px;
                border-radius: 12px;
            }

            h1 {
                font-size: 26px;
            }

            .subtitle {
                margin-bottom: 25px;
            }

        }

        @media (max-width: 360px) {

            .container {
                padding: 23px 16px;
            }

            h1 {
                font-size: 24px;
            }

            .login,
            .back {
                font-size: 14px;
            }

        }

    </style>

</head>

<body>

<div class="container">

    <h1>
        Student Registration
    </h1>

    <div class="subtitle">
        Create your UCGS account
    </div>

    <?php if ($message != "") { ?>

        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php } ?>

    <form method="POST">

        <label>
            Full Name
        </label>

        <input
            type="text"
            name="name"
            placeholder="Enter your full name"
            required
        >

        <label>
            College Email
        </label>

        <input
            type="email"
            name="email"
            placeholder="yourname@tmu.ac.in"
            required
        >

        <label>
            Password
        </label>

        <input
            type="password"
            name="password"
            placeholder="Create a password"
            required
        >

        <button type="submit">
            Create Account
        </button>

    </form>

    <div class="login">

        Already have an account?

        <a href="login.php">
            Student Login
        </a>

    </div>

    <div class="back">

        <a href="../index.php">
            ← Back to Home
        </a>

    </div>

</div>

</body>

</html>