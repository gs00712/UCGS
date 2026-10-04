<?php

session_start();

require_once __DIR__ . "/../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare(
        "SELECT * FROM students WHERE email = ?"
    );

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $student = $result->fetch_assoc();

        if (password_verify($password, $student["password"])) {

            $_SESSION["student_id"] = $student["id"];
            $_SESSION["student_name"] = $student["name"];
            $_SESSION["student_email"] = $student["email"];

            header("Location: dashboard.php");
            exit;

        } else {

            $message = "Invalid email or password.";

        }

    } else {

        $message = "Invalid email or password.";

    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Login - UCGS</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #050505;
            color: white;
            font-family: Arial, sans-serif;
        }

        .container {
            width: 100%;
            max-width: 430px;
            background: #111;
            padding: 35px;
            border: 1px solid #222;
            border-radius: 15px;
            box-shadow: 0 0 25px rgba(0, 229, 255, 0.15);
        }

        h1 {
            margin: 0;
            text-align: center;
            color: #00e5ff;
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
            background: #050505;
            color: white;
            border: 1px solid #333;
            border-radius: 8px;
            font-size: 16px;
        }

        input:focus {
            outline: none;
            border-color: #00e5ff;
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
        }

        button:hover {
            background: #00ff88;
        }

        .message {
            text-align: center;
            color: #ff5555;
            margin-bottom: 20px;
        }

        .register {
            text-align: center;
            margin-top: 25px;
            color: #aaa;
        }

        .register a {
            color: #00e5ff;
            text-decoration: none;
        }

        .back {
            text-align: center;
            margin-top: 20px;
        }

        .back a {
            color: #00ff88;
            text-decoration: none;
        }

        @media (max-width: 500px) {

            body {
                padding: 12px;
            }

            .container {
                padding: 25px 18px;
            }

            h1 {
                font-size: 26px;
            }

        }

    </style>

</head>

<body>

<div class="container">

    <h1>Student Login</h1>

    <div class="subtitle">
        UCGS
    </div>

    <?php if ($message != "") { ?>

        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php } ?>

    <form method="POST">

        <label>College Email</label>

        <input
            type="email"
            name="email"
            placeholder="yourname@tmu.ac.in"
            required
        >

        <label>Password</label>

        <input
            type="password"
            name="password"
            placeholder="Enter your password"
            required
        >

        <button type="submit">
            Login
        </button>

    </form>

    <div class="register">

        New Student?

        <a href="register.php">
            Register Here
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