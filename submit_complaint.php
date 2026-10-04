<?php

session_start();

require_once __DIR__ . "/../config/database.php";

if (!isset($_SESSION["student_id"])) {
    header("Location: login.php");
    exit;
}

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["complaint_title"] ?? "");
    $department = trim($_POST["department"] ?? "");
    $complaint_text = trim($_POST["complaint_text"] ?? "");

    if ($title === "" || $department === "" || $complaint_text === "") {

        $message = "Please fill in all fields.";
        $message_type = "error";

    } else {

        $student_id = $_SESSION["student_id"];

        $sql = "INSERT INTO complaints
                (student_id, complaint_title, complaint_text, department)
                VALUES (?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        if ($stmt) {

            $stmt->bind_param(
                "isss",
                $student_id,
                $title,
                $complaint_text,
                $department
            );

            if ($stmt->execute()) {

                $complaint_id = $stmt->insert_id;

                $message = "Complaint submitted successfully! Your Complaint ID is #" . $complaint_id;
                $message_type = "success";

                $title = "";
                $department = "";
                $complaint_text = "";

            } else {

                $message = "Unable to submit complaint. Please try again.";
                $message_type = "error";
            }

            $stmt->close();

        } else {

            $message = "Database error. Please check the complaints table.";
            $message_type = "error";
        }
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

    <title>Submit Complaint - UCGS</title>

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
            max-width: 760px;
            margin: 45px auto;
            padding: 0 20px;
        }

        .box {
            background: #222;
            padding: 35px;
            border-radius: 15px;
            border: 1px solid #444;
        }

        h1 {
            text-align: center;
            color: #00ffff;
            margin: 0;
            font-size: 30px;
        }

        .subtitle {
            text-align: center;
            color: #aaa;
            margin: 12px 0 35px;
            line-height: 1.5;
        }

        .message {
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 8px;
            text-align: center;
            line-height: 1.5;
        }

        .success {
            background: #063d28;
            color: #00ff88;
            border: 1px solid #00ff88;
        }

        .error {
            background: #3d1010;
            color: #ff7777;
            border: 1px solid #ff5555;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #fff;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 14px;
            background: #333;
            color: white;
            border: 1px solid #555;
            border-radius: 7px;
            font-size: 16px;
            outline: none;
        }

        input,
        select {
            margin-bottom: 25px;
        }

        textarea {
            min-height: 160px;
            margin-bottom: 25px;
            resize: vertical;
            font-family: Arial, sans-serif;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #00ffff;
            box-shadow: 0 0 8px rgba(0, 255, 255, 0.2);
        }

        option {
            background: #222;
            color: white;
        }

        .submit-button {
            width: 100%;
            padding: 15px;
            background: #00ff88;
            color: #000;
            border: none;
            border-radius: 7px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .submit-button:hover {
            background: #00ffff;
            box-shadow: 0 0 15px rgba(0, 255, 255, 0.35);
        }

        .note {
            margin-top: 25px;
            padding: 15px;
            background: #181818;
            border-left: 4px solid #00ffff;
            border-radius: 5px;
        }

        .note p {
            margin: 0;
            color: #aaa;
            line-height: 1.6;
            font-size: 13px;
        }

        .note strong {
            color: #00ffff;
        }

        .links {
            text-align: center;
            margin-top: 25px;
            display: flex;
            justify-content: center;
            gap: 25px;
            flex-wrap: wrap;
        }

        .links a {
            text-decoration: none;
        }

        .dashboard-link {
            color: #00ffff;
        }

        .complaints-link {
            color: #00ff88;
        }

        @media (max-width: 600px) {

            .header {
                padding: 16px 20px;
            }

            .logo {
                font-size: 24px;
            }

            .main {
                margin: 25px auto;
                padding: 0 12px;
            }

            .box {
                padding: 25px 18px;
                border-radius: 12px;
            }

            h1 {
                font-size: 26px;
            }

            .subtitle {
                margin-bottom: 28px;
            }

            .links {
                flex-direction: column;
                gap: 15px;
            }

        }

        @media (max-width: 380px) {

            .main {
                padding: 0 8px;
            }

            .box {
                padding: 22px 15px;
            }

            h1 {
                font-size: 24px;
            }

            input,
            select,
            textarea {
                font-size: 16px;
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

    <div class="box">

        <h1>
            Submit Complaint
        </h1>

        <div class="subtitle">
            Raise your concern and send it to the appropriate department.
        </div>


        <?php if ($message !== "") { ?>

            <div class="message <?php echo $message_type; ?>">

                <?php echo htmlspecialchars($message); ?>

            </div>

        <?php } ?>


        <form method="POST">


            <label>
                Complaint Title
            </label>

            <input
                type="text"
                name="complaint_title"
                value="<?php echo htmlspecialchars($title ?? ''); ?>"
                placeholder="Enter complaint title"
                required
            >


            <label>
                Department
            </label>

            <select
                name="department"
                required
            >

                <option value="">
                    Select Department
                </option>

                <option value="Academic"
                    <?php if (($department ?? "") === "Academic") echo "selected"; ?>>
                    Academic
                </option>

                <option value="Examination"
                    <?php if (($department ?? "") === "Examination") echo "selected"; ?>>
                    Examination
                </option>

                <option value="Accounts"
                    <?php if (($department ?? "") === "Accounts") echo "selected"; ?>>
                    Accounts
                </option>

                <option value="Hostel"
                    <?php if (($department ?? "") === "Hostel") echo "selected"; ?>>
                    Hostel
                </option>

                <option value="Library"
                    <?php if (($department ?? "") === "Library") echo "selected"; ?>>
                    Library
                </option>

                <option value="Transport"
                    <?php if (($department ?? "") === "Transport") echo "selected"; ?>>
                    Transport
                </option>

                <option value="Administration"
                    <?php if (($department ?? "") === "Administration") echo "selected"; ?>>
                    Administration
                </option>

                <option value="Other"
                    <?php if (($department ?? "") === "Other") echo "selected"; ?>>
                    Other
                </option>

            </select>


            <label>
                Complaint Details
            </label>

            <textarea
                name="complaint_text"
                placeholder="Describe your complaint in detail..."
                required
            ><?php echo htmlspecialchars($complaint_text ?? ''); ?></textarea>


            <button
                type="submit"
                class="submit-button"
            >
                Submit Complaint
            </button>

        </form>


        <div class="note">

            <p>

                <strong>Note:</strong>

                Your Complaint ID, submission date, submission time
                and initial status will be recorded automatically.

            </p>

        </div>


        <div class="links">

            <a
                href="dashboard.php"
                class="dashboard-link"
            >
                ← Back to Dashboard
            </a>

            <a
                href="my_complaints.php"
                class="complaints-link"
            >
                My Complaints →
            </a>

        </div>

    </div>

</div>

</body>

</html>