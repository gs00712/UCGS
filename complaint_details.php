<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/../config/database.php";

$id = $_GET["id"] ?? "";

if (!is_numeric($id)) {
    die("Invalid complaint ID.");
}

$stmt = $conn->prepare(
    "SELECT
        complaints.*,
        students.name AS student_name,
        students.email AS student_email
     FROM complaints
     INNER JOIN students
        ON complaints.student_id = students.id
     WHERE complaints.id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Complaint not found.");
}

$complaint = $result->fetch_assoc();


$history_stmt = $conn->prepare(
    "SELECT *
     FROM complaint_status_history
     WHERE complaint_id = ?
     ORDER BY changed_at ASC"
);

$history_stmt->bind_param("i", $id);
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

    <title>Complaint Details - UCGS</title>


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

            max-width: 900px;

            margin: 40px auto;

            padding: 0 20px;
        }


        h1 {
            color: #00e5ff;

            margin: 0 0 25px;

            font-size: 32px;

            word-break: break-word;
        }


        .card {
            background: #111;

            border: 1px solid #222;

            border-radius: 12px;

            padding: 25px;

            margin-bottom: 20px;
        }


        .row {
            margin-bottom: 20px;
        }


        .row:last-child {
            margin-bottom: 0;
        }


        .label {
            color: #00ff88;

            font-weight: bold;

            margin-bottom: 7px;

            font-size: 14px;
        }


        .value {
            color: #ddd;

            line-height: 1.6;

            overflow-wrap: anywhere;
        }


        .complaint-text {
            white-space: pre-wrap;

            line-height: 1.7;

            color: #ddd;

            overflow-wrap: anywhere;
        }


        .status {
            color: #00e5ff;

            font-weight: bold;

            line-height: 1.5;
        }


        .history-title {
            color: #00e5ff;

            margin-top: 0;

            margin-bottom: 25px;
        }


        .history-item {
            border-left: 3px solid #00e5ff;

            padding: 12px 0 12px 15px;

            margin-bottom: 18px;

            background: #0b0b0b;

            border-radius: 0 7px 7px 0;
        }


        .history-item:last-child {
            margin-bottom: 0;
        }


        .history-status {
            color: #00ff88;

            font-weight: bold;

            line-height: 1.5;

            overflow-wrap: anywhere;
        }


        .history-info {
            color: #aaa;

            margin-top: 7px;

            line-height: 1.6;

            font-size: 13px;

            overflow-wrap: anywhere;
        }


        .buttons {
            display: flex;

            gap: 10px;

            flex-wrap: wrap;

            margin-bottom: 20px;
        }


        .back {
            display: inline-block;

            padding: 11px 18px;

            background: #00e5ff;

            color: #000;

            text-decoration: none;

            border-radius: 7px;

            font-weight: bold;

            transition: 0.3s;
        }


        .back:hover {
            background: #00ff88;
        }


        .pdf-button {
            background: #00ff88;
        }


        .pdf-button:hover {
            background: #00e5ff;
        }


        .empty-history {
            color: #aaa;

            line-height: 1.6;

            margin: 0;
        }


        /* MOBILE */

        @media (max-width: 600px) {

            .container {
                margin: 25px auto;

                padding: 0 13px;
            }


            h1 {
                font-size: 26px;

                margin-bottom: 20px;
            }


            .card {
                padding: 20px 17px;

                border-radius: 10px;
            }


            .row {
                margin-bottom: 18px;
            }


            .label {
                font-size: 13px;
            }


            .value,
            .complaint-text {
                font-size: 14px;

                line-height: 1.6;
            }


            .history-title {
                font-size: 21px;
            }


            .buttons {
                flex-direction: column;
            }


            .back {
                width: 100%;

                text-align: center;
            }

        }


        /* SMALL MOBILE */

        @media (max-width: 380px) {

            .container {
                padding: 0 10px;

                margin-top: 20px;
            }


            h1 {
                font-size: 23px;
            }


            .card {
                padding: 17px 14px;
            }


            .history-item {
                padding-left: 12px;
            }


            .history-info {
                font-size: 12px;
            }

        }

    </style>

</head>


<body>


<div class="container">


    <h1>

        Complaint #

        <?php echo htmlspecialchars($complaint["id"]); ?>

    </h1>


    <!-- COMPLAINT DETAILS -->

    <div class="card">


        <div class="row">

            <div class="label">

                Student Name

            </div>


            <div class="value">

                <?php echo htmlspecialchars($complaint["student_name"]); ?>

            </div>

        </div>


        <div class="row">

            <div class="label">

                Student Email

            </div>


            <div class="value">

                <?php echo htmlspecialchars($complaint["student_email"]); ?>

            </div>

        </div>


        <div class="row">

            <div class="label">

                Complaint Title

            </div>


            <div class="value">

                <?php echo htmlspecialchars($complaint["complaint_title"]); ?>

            </div>

        </div>


        <div class="row">

            <div class="label">

                Department

            </div>


            <div class="value">

                <?php echo htmlspecialchars($complaint["department"]); ?>

            </div>

        </div>


        <div class="row">

            <div class="label">

                Current Status

            </div>


            <div class="status">

                <?php echo htmlspecialchars($complaint["status"]); ?>

            </div>

        </div>


        <div class="row">

            <div class="label">

                Submitted On

            </div>


            <div class="value">

                <?php echo htmlspecialchars($complaint["created_at"]); ?>

            </div>

        </div>


        <div class="row">

            <div class="label">

                Last Updated

            </div>


            <div class="value">

                <?php echo htmlspecialchars($complaint["updated_at"]); ?>

            </div>

        </div>


        <div class="row">

            <div class="label">

                Complaint

            </div>


            <div class="complaint-text">

                <?php

                echo htmlspecialchars(
                    $complaint["complaint_text"]
                );

                ?>

            </div>

        </div>


    </div>


    <!-- STATUS HISTORY -->

    <div class="card">


        <h2 class="history-title">

            Status History

        </h2>


        <?php if ($history_result->num_rows > 0): ?>


            <?php while ($history = $history_result->fetch_assoc()): ?>


                <div class="history-item">


                    <div class="history-status">

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

                    </div>


                    <div class="history-info">

                        Changed by:

                        <?php

                        echo htmlspecialchars(
                            $history["changed_by"]
                        );

                        ?>


                        <br>


                        Date:

                        <?php

                        echo htmlspecialchars(
                            $history["changed_at"]
                        );

                        ?>

                    </div>


                </div>


            <?php endwhile; ?>


        <?php else: ?>


            <p class="empty-history">

                No status changes recorded yet.

            </p>


        <?php endif; ?>


    </div>


    <!-- BUTTONS -->

    <div class="buttons">


        <?php if ($complaint["status"] === "Resolved"): ?>


            <a
                href="../reports/download_pdf.php?id=<?php echo $complaint["id"]; ?>"
                class="back pdf-button"
            >

                📄 Download PDF Report

            </a>


        <?php endif; ?>


        <a
            href="dashboard.php"
            class="back"
        >

            ← Back to Dashboard

        </a>


    </div>


</div>


</body>

</html>