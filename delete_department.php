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

/* Check department exists */
$check = $conn->prepare(
    "SELECT id FROM departments WHERE id = ?"
);

$check->bind_param("i", $id);
$check->execute();

$result = $check->get_result();

if ($result->num_rows === 0) {
    die("Department not found.");
}

/* Delete department */
$delete = $conn->prepare(
    "DELETE FROM departments WHERE id = ?"
);

$delete->bind_param("i", $id);

if ($delete->execute()) {

    header("Location: manage_departments.php");
    exit;

} else {

    die("Unable to delete department.");

}

?>