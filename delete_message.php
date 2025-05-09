<?php
$conn = new mysqli("localhost", "root", "", "ethiopia");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn->query("DELETE FROM contact_messages WHERE id = $id");
}

header("Location: manage_contacts.php");
exit();
?>