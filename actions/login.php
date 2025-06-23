<?php 
session_start();
require_once "../functions/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = strtolower(trim($_POST["email"]));
    $password = trim($_POST["password"]);
    
    // Fetch user by email
    $stmt = $conn->prepare("SELECT user_id, name, password, is_admin FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows === 1){
        $stmt->bind_result($user_id, $name, $hash, $is_admin);
        $stmt->fetch();
         // Verify password
        if(password_verify($password, $hash)){
            // Set session variables
            $_SESSION["user_id"]= $user_id;
            $_SESSION["is_admin"] = $is_admin;
            $_SESSION["name"]=$name;
            // Redirect to welcome page
            header("Location: ../index.php");
            exit(); // Stop script after redirection
        }else{
            header("Location: ../index.php?error=login");
            exit();
        }
    }else{
        header("Location: ../index.php?error=login");
        exit();
    }
}
?>