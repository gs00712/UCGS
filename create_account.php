```php
<?php

require_once "../config/database.php";

$department_name = "Academic";
$email = "academic@tmu.ac.in";
$password = "password";

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO departments
        (department_name, email, password)
        VALUES (?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "sss",
    $department_name,
    $email,
    $hashed_password
);

if ($stmt->execute()) {

    echo "Department account created successfully!<br><br>";
    echo "Email: academic@tmu.ac.in<br>";
    echo "Password: password";

} else {

    echo "Error: " . $conn->error;

}

$stmt->close();

?>
```
