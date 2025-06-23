<?php
//Database connection variables
    $host = "localhost";
    $db_user = "your_db_user";
    $db_password = "your_db_password";
    $db_name = "your_database_name";

     // Create connection
    $conn = new mysqli($host, $db_user, $db_password, $db_name);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>