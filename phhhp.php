<?php
$servername = "localhost";
$username = "username";  // Use your MySQL username (often "root" for local development)
$password = "password";  // Use your MySQL password
$dbname = "student";     // Database name changed to "student"

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    die("Connection error. Please contact support.");
}

// Create student database
$sql = "CREATE DATABASE IF NOT EXISTS $dbname 
        CHARACTER SET utf8mb4 
        COLLATE utf8mb4_unicode_ci";

if ($conn->query($sql)) {  // Fixed this line
    echo "Database '$dbname' created successfully or already exists";

    // Select the student database
    $conn->select_db($dbname);
    echo "<br>Now using database: $dbname";

    // (Optional) Create tables in the student database
    $sql = "CREATE TABLE IF NOT EXISTS student_info (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            first_name VARCHAR(30) NOT NULL,
            last_name VARCHAR(30) NOT NULL,
            email VARCHAR(50) UNIQUE NOT NULL,
            enrollment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

    if ($conn->query($sql)) {
        echo "<br>Table 'student_info' created successfully";
    } else {
        echo "<br>Error creating table: " . $conn->error;
    }
} else {
    echo "Error creating database: " . $conn->error;
}

// Close connection
$conn->close();
