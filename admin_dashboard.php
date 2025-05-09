<?php
session_start();
// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Explore Ethiopia</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background-color: #f4f4f4; }
        header { background-color: #0b3d91; color: white; padding: 20px; text-align: center; }
        .dashboard { max-width: 800px; margin: 30px auto; padding: 30px; background: white; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .logout { text-align: right; margin-bottom: 20px; }
        .logout a { color: #0b3d91; text-decoration: none; font-weight: bold; }
        h2 { color: #0b3d91; }
        .admin-buttons { margin-top: 20px; display: flex; gap: 10px; flex-wrap: wrap; }
        .admin-buttons button { 
            background-color: #0b3d91; color: white; border: none; padding: 12px 18px; 
            border-radius: 5px; cursor: pointer; font-weight: bold; transition: background-color 0.3s ease;
        }
        .admin-buttons button:hover { background-color: #072e6a; }
    </style>
</head>
<body>
    <header>
        <h1>Admin Dashboard - Explore Ethiopia</h1>
    </header>
    <div class="dashboard">
        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
        <h2>Welcome, Admin 👋</h2>
        <div class="admin-buttons">
            <button onclick="window.location.href='manage_contacts.php'">📬 Manage Contact Messages</button>
            <button onclick="window.location.href='manage_places.php'">📍 Manage Tourist Places</button>
            <button onclick="window.location.href='Manage_Users.php'">🖼️ Manage Users</button>
            
        </div>
    </div>
</body>
</html>