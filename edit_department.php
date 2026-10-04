<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/../config/database.php";

$id = $_GET["id"] ?? "";

if (!is_numeric($id)) {
    die("Invalid department ID.");
}


/* Get department */

$stmt = $conn->prepare(
    "SELECT * FROM departments WHERE id = ?"
);

$stmt->bind_param("i", $id);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Department not found.");
}

$department = $result->fetch_assoc();

$message = "";


/* Update */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $department_name = trim($_POST["department_name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (
        $department_name === "" ||
        $email === ""
    ) {

        $message = "Department name and email are required.";

    } else {

        if ($password !== "") {

            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $update = $conn->prepare(
                "UPDATE departments
                 SET department_name = ?,
                     email = ?,
                     password = ?
                 WHERE id = ?"
            );

            $update->bind_param(
                "sssi",
                $department_name,
                $email,
                $hashed_password,
                $id
            );

        } else {

            $update = $conn->prepare(
                "UPDATE departments
                 SET department_name = ?,
                     email = ?
                 WHERE id = ?"
            );

            $update->bind_param(
                "ssi",
                $department_name,
                $email,
                $id
            );
        }


        if ($update->execute()) {

            $message = "Department updated successfully.";

            $department["department_name"] = $department_name;

            $department["email"] = $email;

        } else {

            $message = "Unable to update department.";

        }

        $update->close();
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

    <title>Edit Department - UCGS</title>


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


        .container {
            width: 100%;

            max-width: 550px;

            margin: 60px auto;

            padding: 0 20px;
        }


        .card {
            background: #111;

            border: 1px solid #222;

            border-radius: 12px;

            padding: 30px;
        }


        h1 {
            color: #00e5ff;

            margin-top: 0;

            margin-bottom: 25px;

            font-size: 30px;
        }


        label {
            display: block;

            color: #00ff88;

            margin-bottom: 8px;

            font-weight: bold;
        }


        input {
            width: 100%;

            padding: 13px;

            margin-bottom: 20px;

            background: #080808;

            color: white;

            border: 1px solid #333;

            border-radius: 7px;

            font-size: 15px;
        }


        input:focus {
            outline: none;

            border-color: #00e5ff;

            box-shadow:
                0 0 10px
                rgba(0, 229, 255, 0.2);
        }


        button {
            width: 100%;

            padding: 14px;

            background: #00e5ff;

            color: #000;

            border: none;

            border-radius: 7px;

            font-weight: bold;

            cursor: pointer;

            font-size: 15px;

            transition: 0.3s;
        }


        button:hover {
            background: #00ff88;

            box-shadow:
                0 0 15px
                rgba(0, 255, 136, 0.3);
        }


        .message {
            color: #00ff88;

            margin-bottom: 20px;

            font-weight: bold;

            line-height: 1.5;
        }


        .back {
            display: inline-block;

            margin-top: 20px;

            color: #00ff88;

            text-decoration: none;

            font-weight: bold;

            line-height: 1.5;
        }


        .back:hover {
            color: #00e5ff;
        }


        .note {
            color: #888;

            font-size: 13px;

            line-height: 1.5;

            margin-top: -10px;

            margin-bottom: 20px;
        }


        /* MOBILE */

        @media (max-width: 600px) {

            .container {
                margin: 30px auto;

                padding: 0 14px;
            }


            .card {
                padding: 22px 18px;

                border-radius: 10px;
            }


            h1 {
                font-size: 26px;

                margin-bottom: 22px;
            }


            input {
                font-size: 16px;

                padding: 13px;

                margin-bottom: 18px;
            }


            button {
                padding: 13px;
            }

        }


        /* SMALL MOBILE */

        @media (max-width: 380px) {

            .container {
                margin: 20px auto;

                padding: 0 10px;
            }


            .card {
                padding: 18px 14px;
            }


            h1 {
                font-size: 23px;
            }


            label {
                font-size: 14px;
            }


            .note {
                font-size: 12px;
            }

        }

    </style>

</head>


<body>


<div class="container">


    <div class="card">


        <h1>
            Edit Department
        </h1>


        <?php if ($message !== ""): ?>

            <div class="message">

                <?php echo htmlspecialchars($message); ?>

            </div>

        <?php endif; ?>


        <form method="POST">


            <label>
                Department Name
            </label>


            <input
                type="text"
                name="department_name"
                value="<?php echo htmlspecialchars($department["department_name"]); ?>"
                required
            >


            <label>
                Department Email
            </label>


            <input
                type="email"
                name="email"
                value="<?php echo htmlspecialchars($department["email"]); ?>"
                required
            >


            <label>
                New Password
            </label>


            <input
                type="password"
                name="password"
                placeholder="Leave blank to keep current password"
            >


            <div class="note">

                Password blank chhodoge to purana password same rahega.

            </div>


            <button type="submit">

                Update Department

            </button>


        </form>


        <a
            href="manage_departments.php"
            class="back"
        >

            ← Back to Manage Departments

        </a>


    </div>


</div>


</body>

</html>