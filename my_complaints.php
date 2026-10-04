<?php

session_start();

require_once __DIR__ . "/../config/database.php";

if (!isset($_SESSION["student_id"])) {
    header("Location: login.php");
    exit;
}

$student_id = $_SESSION["student_id"];

$sql = "SELECT
            id,
            complaint_title,
            complaint_text,
            department,
            status,
            created_at,
            updated_at
        FROM complaints
        WHERE student_id = ?
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();

$result = $stmt->get_result();

function getStatusHistory($conn, $complaint_id)
{
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

    return $history_stmt->get_result();
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

    <title>My Complaints - UCGS</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #111;
            color: white;
            font-family: Arial, sans-serif;
            overflow-x: hidden;
        }

        .header {
            background: #000;
            padding: 18px 50px;
            border-bottom: 2px solid #00ffff;
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #00ffff;
        }

        .logo span {
            color: #00ff88;
        }

        .main {
            width: 100%;
            max-width: 950px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .heading {
            text-align: center;
            margin-bottom: 35px;
        }

        .heading h1 {
            color: #00ffff;
            margin: 0 0 10px;
            font-size: 30px;
        }

        .heading p {
            color: #aaa;
            margin: 0;
        }

        .count {
            margin-bottom: 20px;
            color: #aaa;
        }

        .count strong {
            color: #00ff88;
        }

        .complaint-card {
            background: #222;
            border: 1px solid #444;
            border-radius: 14px;
            padding: 25px;
            margin-bottom: 25px;
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .label {
            color: #888;
            font-size: 12px;
            margin-bottom: 6px;
        }

        .id-label {
            color: #00ffff;
        }

        .complaint-id {
            font-size: 22px;
            font-weight: bold;
        }

        .status {
            padding: 9px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        hr {
            border: 0;
            border-top: 1px solid #3a3a3a;
            margin: 22px 0;
        }

        .section {
            margin-bottom: 20px;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            line-height: 1.5;
            word-break: break-word;
        }

        .department {
            color: #00ff88;
        }

        .details {
            color: #ccc;
            line-height: 1.7;
            background: #181818;
            padding: 15px;
            border-radius: 8px;
            white-space: normal;
            word-break: break-word;
        }

        .dates {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }

        .date-value {
            color: #ddd;
            line-height: 1.5;
        }

        .timeline {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #333;
        }

        .timeline h3 {
            color: #00ffff;
            margin: 0 0 18px;
        }

        .timeline-item {
            border-left: 3px solid #00ff88;
            padding-left: 18px;
            margin-bottom: 18px;
        }

        .timeline-status {
            color: #00ff88;
            font-weight: bold;
        }

        .timeline-date {
            color: #aaa;
            font-size: 13px;
            margin-top: 5px;
        }

        .timeline-by {
            color: #777;
            font-size: 13px;
            margin-top: 3px;
            word-break: break-word;
        }

        .no-history {
            color: #888;
            font-size: 14px;
        }

        .empty {
            background: #222;
            border: 1px solid #444;
            border-radius: 14px;
            padding: 50px 25px;
            text-align: center;
        }

        .empty-icon {
            font-size: 45px;
            margin-bottom: 20px;
        }

        .empty h2 {
            color: #00ffff;
        }

        .empty p {
            color: #999;
            margin-bottom: 25px;
        }

        .button {
            display: inline-block;
            background: #00ff88;
            color: #000;
            padding: 13px 22px;
            border-radius: 7px;
            text-decoration: none;
            font-weight: bold;
        }

        .navigation {
            text-align: center;
            margin-top: 30px;
            display: flex;
            justify-content: center;
            gap: 25px;
            flex-wrap: wrap;
        }

        .dashboard-link {
            color: #00ffff;
            text-decoration: none;
        }

        .new-link {
            color: #00ff88;
            text-decoration: none;
        }

        .footer {
            text-align: center;
            padding: 30px 20px;
            color: #555;
            font-size: 13px;
        }

        @media (max-width: 600px) {

            .header {
                padding: 16px 20px;
            }

            .logo {
                font-size: 24px;
            }

            .main {
                margin: 30px auto;
                padding: 0 12px;
            }

            .heading h1 {
                font-size: 26px;
            }

            .complaint-card {
                padding: 20px 17px;
            }

            .top {
                align-items: flex-start;
                flex-direction: column;
            }

            .status {
                align-self: flex-start;
            }

            .dates {
                grid-template-columns: 1fr;
                gap: 18px;
            }

            .title {
                font-size: 18px;
            }

            .navigation {
                flex-direction: column;
                gap: 15px;
            }

            .empty {
                padding: 40px 20px;
            }

            .button {
                width: 100%;
                text-align: center;
            }

        }

        @media (max-width: 380px) {

            .main {
                padding: 0 8px;
            }

            .complaint-card {
                padding: 18px 14px;
            }

            .heading h1 {
                font-size: 24px;
            }

            .complaint-id {
                font-size: 20px;
            }

        }

    </style>

</head>

<body>

<div class="header">

    <div class="logo">
        UC<span>GS</span>
    </div>

</div>


<div class="main">

    <div class="heading">

        <h1>
            My Complaints
        </h1>

        <p>
            View and track all your submitted complaints.
        </p>

    </div>


    <?php if ($result->num_rows > 0) { ?>

        <div class="count">

            Total Complaints:

            <strong>
                <?php echo $result->num_rows; ?>
            </strong>

        </div>


        <?php while ($complaint = $result->fetch_assoc()) { ?>

            <?php

            $status = $complaint["status"];

            if ($status === "Pending") {

                $status_color = "#ffd43b";

            } elseif ($status === "In Progress") {

                $status_color = "#00ffff";

            } else {

                $status_color = "#00ff88";

            }

            $history_result = getStatusHistory(
                $conn,
                $complaint["id"]
            );

            ?>


            <div class="complaint-card">

                <div class="top">

                    <div>

                        <div class="label id-label">
                            COMPLAINT ID
                        </div>

                        <div class="complaint-id">
                            #<?php echo $complaint["id"]; ?>
                        </div>

                    </div>


                    <div
                        class="status"
                        style="
                            border:1px solid <?php echo $status_color; ?>;
                            color:<?php echo $status_color; ?>;
                        "
                    >

                        <?php echo htmlspecialchars($status); ?>

                    </div>

                </div>


                <hr>


                <div class="section">

                    <div class="label">
                        COMPLAINT TITLE
                    </div>

                    <div class="title">
                        <?php
                        echo htmlspecialchars(
                            $complaint["complaint_title"]
                        );
                        ?>
                    </div>

                </div>


                <div class="section">

                    <div class="label">
                        DEPARTMENT
                    </div>

                    <div class="department">
                        <?php
                        echo htmlspecialchars(
                            $complaint["department"]
                        );
                        ?>
                    </div>

                </div>


                <div class="section">

                    <div class="label">
                        COMPLAINT DETAILS
                    </div>

                    <div class="details">
                        <?php
                        echo nl2br(
                            htmlspecialchars(
                                $complaint["complaint_text"]
                            )
                        );
                        ?>
                    </div>

                </div>


                <div class="dates">

                    <div>

                        <div class="label">
                            SUBMITTED ON
                        </div>

                        <div class="date-value">

                            <?php
                            echo date(
                                "d M Y, h:i A",
                                strtotime($complaint["created_at"])
                            );
                            ?>

                        </div>

                    </div>


                    <div>

                        <div class="label">
                            LAST UPDATED
                        </div>

                        <div class="date-value">

                            <?php
                            echo date(
                                "d M Y, h:i A",
                                strtotime($complaint["updated_at"])
                            );
                            ?>

                        </div>

                    </div>

                </div>


                <div class="timeline">

                    <h3>
                        Status Timeline
                    </h3>


                    <?php if ($history_result && $history_result->num_rows > 0) { ?>

                        <?php while ($history = $history_result->fetch_assoc()) { ?>

                            <div class="timeline-item">

                                <div class="timeline-status">

                                    <?php
                                    echo htmlspecialchars(
                                        $history["new_status"]
                                    );
                                    ?>

                                </div>

                                <div class="timeline-date">

                                    <?php
                                    echo date(
                                        "d M Y, h:i A",
                                        strtotime(
                                            $history["changed_at"]
                                        )
                                    );
                                    ?>

                                </div>

                                <div class="timeline-by">

                                    Updated by:

                                    <?php
                                    echo htmlspecialchars(
                                        $history["changed_by"]
                                    );
                                    ?>

                                </div>

                            </div>

                        <?php } ?>

                    <?php } else { ?>

                        <div class="no-history">

                            No status changes recorded yet.

                        </div>

                    <?php } ?>

                </div>

            </div>

        <?php } ?>


    <?php } else { ?>


        <div class="empty">

            <div class="empty-icon">
                📋
            </div>

            <h2>
                No Complaints Yet
            </h2>

            <p>
                You have not submitted any complaint yet.
            </p>

            <a
                href="submit_complaint.php"
                class="button"
            >
                Submit Your First Complaint
            </a>

        </div>


    <?php } ?>


    <div class="navigation">

        <a
            href="dashboard.php"
            class="dashboard-link"
        >
            ← Back to Dashboard
        </a>

        <a
            href="submit_complaint.php"
            class="new-link"
        >
            + New Complaint
        </a>

    </div>

</div>


<div class="footer">

    UCGS © 2026 | University Complaint & Grievance System

</div>


</body>

</html>

<?php

$stmt->close();

?>