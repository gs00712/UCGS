<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/../config/database.php";

$message = "";


/* Add Department */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $department_name = trim($_POST["department_name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (
        $department_name === "" ||
        $email === "" ||
        $password === ""
    ) {

        $message = "All fields are required.";

    } else {

        $check = $conn->prepare(
            "SELECT id FROM departments WHERE email = ?"
        );

        $check->bind_param("s", $email);

        $check->execute();

        $check_result = $check->get_result();

        if ($check_result->num_rows > 0) {

            $message = "This email is already registered.";

        } else {

            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $stmt = $conn->prepare(
                "INSERT INTO departments
                (department_name, email, password)
                VALUES (?, ?, ?)"
            );

            $stmt->bind_param(
                "sss",
                $department_name,
                $email,
                $hashed_password
            );

            if ($stmt->execute()) {

                $message = "Department added successfully.";

            } else {

                $message = "Unable to add department.";

            }

            $stmt->close();
        }

        $check->close();
    }
}


/* Get departments */

$result = $conn->query(
    "SELECT *
     FROM departments
     ORDER BY department_name ASC"
);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Manage Departments - UCGS</title>


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

            max-width: 1100px;

            margin: 40px auto;

            padding: 0 20px;
        }


        .back {
            display: inline-block;

            margin-bottom: 25px;

            color: #00e5ff;

            text-decoration: none;

            font-weight: bold;
        }


        .back:hover {
            color: #00ff88;
        }


        h1 {
            color: #00e5ff;

            margin-top: 0;

            margin-bottom: 25px;

            font-size: 32px;
        }


        .card {
            background: #111;

            border: 1px solid #222;

            border-radius: 10px;

            padding: 25px;

            margin-bottom: 25px;

            overflow: hidden;
        }


        .card h2 {
            margin-top: 0;

            margin-bottom: 22px;

            font-size: 21px;
        }


        input {
            display: block;

            padding: 13px;

            width: 100%;

            margin-bottom: 15px;

            background: #080808;

            color: white;

            border: 1px solid #333;

            border-radius: 6px;

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
            padding: 12px 20px;

            background: #00ff88;

            color: #000;

            border: none;

            border-radius: 7px;

            font-weight: bold;

            cursor: pointer;

            transition: 0.3s;

            font-size: 14px;
        }


        button:hover {
            background: #00e5ff;

            box-shadow:
                0 0 15px
                rgba(0, 229, 255, 0.3);
        }


        .message {
            color: #00ff88;

            margin-bottom: 20px;

            font-weight: bold;

            line-height: 1.5;
        }


        .table-wrapper {
            width: 100%;

            overflow-x: auto;

            -webkit-overflow-scrolling: touch;
        }


        table {
            width: 100%;

            min-width: 700px;

            border-collapse: collapse;
        }


        th,
        td {
            padding: 13px;

            border-bottom: 1px solid #222;

            text-align: left;

            vertical-align: middle;
        }


        th {
            color: #00ff88;

            white-space: nowrap;
        }


        td {
            color: #ddd;

            overflow-wrap: anywhere;
        }


        .edit {
            color: #00e5ff;

            text-decoration: none;

            font-weight: bold;
        }


        .edit:hover {
            color: #00ff88;
        }


        .delete {
            color: #ff5555;

            text-decoration: none;

            font-weight: bold;

            margin-left: 10px;
        }


        .delete:hover {
            color: #ff8888;
        }


        .no-data {
            color: #aaa;

            text-align: center;
        }


        /* TABLET */

        @media (max-width: 700px) {

            .container {
                margin: 30px auto;

                padding: 0 15px;
            }


            h1 {
                font-size: 28px;
            }


            .card {
                padding: 20px;
            }


            .card h2 {
                font-size: 19px;
            }


            table {
                min-width: 650px;
            }

        }


        /* MOBILE */

        @media (max-width: 500px) {

            .container {
                margin: 22px auto;

                padding: 0 12px;
            }


            .back {
                margin-bottom: 20px;

                font-size: 14px;
            }


            h1 {
                font-size: 25px;

                margin-bottom: 20px;
            }


            .card {
                padding: 18px 15px;

                border-radius: 9px;

                margin-bottom: 18px;
            }


            .card h2 {
                font-size: 18px;

                line-height: 1.4;
            }


            input {
                padding: 13px;

                font-size: 16px;
            }


            button {
                width: 100%;

                padding: 13px;
            }


            .message {
                font-size: 14px;
            }


            .table-wrapper {
                margin-top: 5px;
            }

        }


        /* SMALL MOBILE */

        @media (max-width: 380px) {

            .container {
                padding: 0 10px;
            }


            h1 {
                font-size: 23px;
            }


            .card {
                padding: 16px 12px;
            }


            .card h2 {
                font-size: 17px;
            }


            table {
                min-width: 620px;
            }

        }

    </style>

</head>


<body>


<div class="container">


    <a
        href="dashboard.php"
        class="back"
    >
        ← Back to Dashboard
    </a>


    <h1>
        Manage Departments
    </h1>


    <?php if ($message !== ""): ?>

        <div class="message">

            <?php echo htmlspecialchars($message); ?>

        </div>

    <?php endif; ?>


    <!-- ADD DEPARTMENT -->

    <div class="card">

        <h2 style="color:#00ff88;">

            Add New Department

        </h2>


        <form method="POST">


            <input
                type="text"
                name="department_name"
                placeholder="Department Name"
                required
            >


            <input
                type="email"
                name="email"
                placeholder="Department Email"
                required
            >


            <input
                type="password"
                name="password"
                placeholder="Department Password"
                required
            >


            <button type="submit">

                Add Department

            </button>


        </form>

    </div>


    <!-- EXISTING DEPARTMENTS -->

    <div class="card">

        <h2 style="color:#00e5ff;">

            Existing Departments

        </h2>


        <div class="table-wrapper">

            <table>


                <tr>

                    <th>ID</th>

                    <th>Department</th>

                    <th>Email</th>

                    <th>Created</th>

                    <th>Action</th>

                </tr>


                <?php if ($result->num_rows > 0): ?>


                    <?php while (
                        $department = $result->fetch_assoc()
                    ): ?>


                        <tr>


                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $department["id"]
                                );

                                ?>

                            </td>


                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $department["department_name"]
                                );

                                ?>

                            </td>


                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $department["email"]
                                );

                                ?>

                            </td>


                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $department["created_at"]
                                );

                                ?>

                            </td>


                            <td>


                                <a
                                    href="edit_department.php?id=<?php echo $department["id"]; ?>"
                                    class="edit"
                                >

                                    Edit

                                </a>


                                <a
                                    href="delete_department.php?id=<?php echo $department["id"]; ?>"
                                    class="delete"
                                    onclick="return confirm('Are you sure you want to delete this department?');"
                                >

                                    Delete

                                </a>


                            </td>


                        </tr>


                    <?php endwhile; ?>


                <?php else: ?>


                    <tr>

                        <td
                            colspan="5"
                            class="no-data"
                        >

                            No departments found.

                        </td>

                    </tr>


                <?php endif; ?>


            </table>

        </div>


    </div>


</div>


</body>

</html>