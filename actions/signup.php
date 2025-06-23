<?php
require_once '../functions/db.php'; // Connect to database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = strtolower(trim($_POST["email"]));
    $phone = trim($_POST["phone"]);
    $password = trim($_POST["password"]);

    // Validate fields
    if (empty($name) || empty($email) || empty($phone) || empty($password)) {
        die("Error: All fields required");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Error: Invalid Email");
    }

    // Check if user exists
    $check = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if($check->num_rows > 0){
        header("Location: ../index.php?error=signup");
    }

    // Hash password
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss",$name, $email, $phone, $hash);

    if($stmt->execute()){
        header("Location: ../index.php?signup=success");
        exit();
    }else{
        header("Location: ../index.php?error=signup");
    }
}

?>