<?php

require_once "../config/database.php";

$department_name = "Examination";
$email = "examination@tmu.ac.in";
$password = "password";

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO departments
        (department_name, email, password)
        VALUES (?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $department_name, $email, $hashed_password);

if ($stmt->execute()) {
    echo "Examination department account created successfully!";
} else {
    echo "Error: " . $stmt->error;
}

?>