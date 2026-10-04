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

/* Check complaint exists */
$check = $conn->prepare(
    "SELECT id FROM complaints WHERE id = ?"
);

$check->bind_param("i", $id);
$check->execute();

$result = $check->get_result();

if ($result->num_rows === 0) {
    die("Complaint not found.");
}

/* Delete status history first */
$history_delete = $conn->prepare(
    "DELETE FROM complaint_status_history
     WHERE complaint_id = ?"
);

$history_delete->bind_param("i", $id);
$history_delete->execute();

/* Delete complaint */
$delete = $conn->prepare(
    "DELETE FROM complaints
     WHERE id = ?"
);

$delete->bind_param("i", $id);

if ($delete->execute()) {

    header("Location: dashboard.php");
    exit;

} else {

    die("Unable to delete complaint.");

}

?>