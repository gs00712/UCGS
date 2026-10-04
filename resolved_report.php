<?php

session_start();

require_once __DIR__ . "/../config/database.php";


/* Check login */

$is_admin = isset($_SESSION["admin_id"]);
$is_department = isset($_SESSION["department_id"]);

if (!$is_admin && !$is_department) {

    header("Location: ../index.php");
    exit;

}


/* Complaint ID */

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    die("Invalid complaint ID.");

}

$complaint_id = (int) $_GET["id"];


/* Get complaint */

$sql = "SELECT
            complaints.id,
            complaints.complaint_title,
            complaints.complaint_text,
            complaints.department,
            complaints.status,
            complaints.created_at,
            complaints.updated_at,
            students.name AS student_name,
            students.email AS student_email
        FROM complaints
        INNER JOIN students
        ON complaints.student_id = students.id
        WHERE complaints.id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $complaint_id);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows === 0) {

    die("Complaint not found.");

}

$complaint = $result->fetch_assoc();


/*
    Department can view only
    its own complaint.

    Admin can view any complaint.
*/

if ($is_department && !$is_admin) {

    $department_name = $_SESSION["department_name"];

    if ($complaint["department"] !== $department_name) {

        die("You do not have permission to view this complaint.");

    }

}


/* Check resolved */

if ($complaint["status"] !== "Resolved") {

    die("This complaint is not resolved yet.");

}


/* Status history */

$history_sql = "SELECT
                    old_status,
                    new_status,
                    changed_by,
                    changed_at
                FROM complaint_status_history
                WHERE complaint_id = ?
                ORDER BY changed_at ASC";

$history_stmt = $conn->prepare($history_sql);

$history_stmt->bind_param("i", $complaint_id);

$history_stmt->execute();

$history_result = $history_stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Resolved Complaint Report</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px 15px;
            background: #000;
            color: #fff;
            font-family: Arial, sans-serif;
        }

        .container {
            width: 100%;
            max-width: 900px;
            margin: auto;
            background: #111;
            padding: 30px;
            border-radius: 12px;
            border: 1px solid #333;
        }

        h1 {
            color: #00ffff;
            text-align: center;
            margin: 0 0 10px;
            font-size: 30px;
        }

        .resolved {
            text-align: center;
            color: #00ff88;
            font-weight: bold;
            margin-bottom: 30px;
            font-size: 16px;
        }

        .section {
            margin-top: 25px;
        }

        .section h2 {
            color: #00ffff;
            border-bottom: 1px solid #333;
            padding-bottom: 8px;
            margin-bottom: 18px;
            font-size: 21px;
        }

        .row {
            margin: 12px 0;
            line-height: 1.6;
            overflow-wrap: anywhere;
        }

        .label {
            color: #aaa;
            font-weight: bold;
        }

        .value {
            color: #fff;
        }

        .history {
            background: #181818;
            padding: 15px;
            margin-top: 10px;
            border-radius: 8px;
            border-left: 3px solid #00ff88;
            line-height: 1.6;
            overflow-wrap: anywhere;
        }

        .buttons {
            text-align: center;
            margin-top: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .btn {
            display: inline-block;
            padding: 12px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            border: none;
            cursor: pointer;
            font-size: 15px;
            transition: 0.3s;
        }

        .print {
            background: #00ffff;
            color: #000;
        }

        .print:hover {
            background: #00ff88;
            box-shadow: 0 0 15px rgba(0, 255, 136, 0.4);
        }

        .back {
            background: #333;
            color: #fff;
        }

        .back:hover {
            background: #444;
        }


        /* Tablet */

        @media (max-width: 700px) {

            body {
                padding: 20px 10px;
            }

            .container {
                padding: 22px 18px;
            }

            h1 {
                font-size: 25px;
            }

            .section h2 {
                font-size: 19px;
            }

            .row {
                font-size: 15px;
            }

            .history {
                font-size: 14px;
            }

        }


        /* Mobile */

        @media (max-width: 500px) {

            body {
                padding: 10px;
            }

            .container {
                padding: 20px 14px;
                border-radius: 10px;
            }

            h1 {
                font-size: 22px;
                line-height: 1.3;
            }

            .resolved {
                font-size: 14px;
                margin-bottom: 22px;
            }

            .section {
                margin-top: 22px;
            }

            .section h2 {
                font-size: 18px;
            }

            .row {
                margin: 15px 0;
                line-height: 1.5;
            }

            .label {
                display: block;
                margin-bottom: 3px;
            }

            .value {
                display: block;
            }

            .history {
                padding: 13px;
                font-size: 14px;
            }

            .buttons {
                flex-direction: column;
                width: 100%;
                gap: 10px;
            }

            .btn {
                width: 100%;
                text-align: center;
                padding: 13px 15px;
            }

        }


        /* Very small mobile */

        @media (max-width: 350px) {

            .container {
                padding: 17px 12px;
            }

            h1 {
                font-size: 20px;
            }

            .section h2 {
                font-size: 17px;
            }

            .row,
            .history {
                font-size: 13px;
            }

        }


        /* Print */

        @media print {

            body {
                background: white;
                color: black;
                padding: 0;
            }

            .container {
                background: white;
                color: black;
                border: none;
                max-width: none;
                padding: 0;
            }

            .buttons {
                display: none;
            }

            .section h2 {
                color: black;
                border-bottom: 1px solid #999;
            }

            .label,
            .value {
                color: black;
            }

            .resolved {
                color: black;
            }

            .history {
                background: white;
                color: black;
                border-left: 3px solid #000;
            }

        }

    </style>

</head>

<body>

<div class="container">

    <h1>
        Resolved Complaint Report
    </h1>

    <div class="resolved">
        STATUS: RESOLVED
    </div>


    <div class="section">

        <h2>
            Complaint Information
        </h2>

        <div class="row">

            <span class="label">
                Complaint ID:
            </span>

            <span class="value">
                #<?php echo htmlspecialchars($complaint["id"]); ?>
            </span>

        </div>

        <div class="row">

            <span class="label">
                Title:
            </span>

            <span class="value">
                <?php echo htmlspecialchars($complaint["complaint_title"]); ?>
            </span>

        </div>

        <div class="row">

            <span class="label">
                Department:
            </span>

            <span class="value">
                <?php echo htmlspecialchars($complaint["department"]); ?>
            </span>

        </div>

        <div class="row">

            <span class="label">
                Complaint Details:
            </span>

            <span class="value">
                <?php
                echo nl2br(
                    htmlspecialchars(
                        $complaint["complaint_text"]
                    )
                );
                ?>
            </span>

        </div>

        <div class="row">

            <span class="label">
                Submitted On:
            </span>

            <span class="value">
                <?php echo htmlspecialchars($complaint["created_at"]); ?>
            </span>

        </div>

        <div class="row">

            <span class="label">
                Last Updated:
            </span>

            <span class="value">
                <?php echo htmlspecialchars($complaint["updated_at"]); ?>
            </span>

        </div>

    </div>


    <div class="section">

        <h2>
            Student Information
        </h2>

        <div class="row">

            <span class="label">
                Student Name:
            </span>

            <span class="value">
                <?php echo htmlspecialchars($complaint["student_name"]); ?>
            </span>

        </div>

        <div class="row">

            <span class="label">
                College Email:
            </span>

            <span class="value">
                <?php echo htmlspecialchars($complaint["student_email"]); ?>
            </span>

        </div>

    </div>


    <div class="section">

        <h2>
            Status History
        </h2>

        <?php if ($history_result->num_rows > 0): ?>

            <?php while ($history = $history_result->fetch_assoc()): ?>

                <div class="history">

                    <strong>

                        <?php
                        echo htmlspecialchars(
                            $history["old_status"]
                        );
                        ?>

                        →

                        <?php
                        echo htmlspecialchars(
                            $history["new_status"]
                        );
                        ?>

                    </strong>

                    <br>

                    Changed By:

                    <?php
                    echo htmlspecialchars(
                        $history["changed_by"]
                    );
                    ?>

                    <br>

                    Date & Time:

                    <?php
                    echo htmlspecialchars(
                        $history["changed_at"]
                    );
                    ?>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <p>
                No status history available.
            </p>

        <?php endif; ?>

    </div>


    <div class="buttons">

        <a
            href="#"
            onclick="window.print(); return false;"
            class="btn print"
        >
            Print / Save as PDF
        </a>


        <a
            href="download_pdf.php?id=<?php echo $complaint["id"]; ?>"
            class="btn print"
        >
            Download PDF
        </a>


        <?php if ($is_admin): ?>

            <a
                href="../admin/dashboard.php"
                class="btn back"
            >
                Back to Admin Dashboard
            </a>

        <?php else: ?>

            <a
                href="../department/dashboard.php"
                class="btn back"
            >
                Back to Department Dashboard
            </a>

        <?php endif; ?>

    </div>

</div>

</body>

</html>