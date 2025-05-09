<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "ethiopia");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$name = $_POST['name'] ?? '';
$location = $_POST['location'] ?? '';
$description = $_POST['description'] ?? '';
$cost = isset($_POST['cost']) ? floatval($_POST['cost']) : 0;
$duration = $_POST['duration'] ?? '';

// Handle image upload
$image = $_FILES['image']['name'] ?? '';
$tmp = $_FILES['image']['tmp_name'] ?? '';
$folder = "uploads/" . basename($image);

// Ensure uploads folder exists
if (!is_dir("uploads")) {
    mkdir("uploads", 0777, true);
}

// Move image and insert data
if ($image && move_uploaded_file($tmp, $folder)) {
    $stmt = $conn->prepare("INSERT INTO places (name, location, description, image, cost, duration, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssssds", $name, $location, $description, $image, $cost, $duration);

    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $stmt->close();
} else {
    die("Image upload failed or no image selected.");
}

$conn->close();

// Redirect after successful insert
header("Location: manage_places.php");
exit();
?>
