<?php
session_start();
$conn = new mysqli("localhost", "root", "", "ethiopia");
$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "Email already registered.";
        } else {
            // Insert new user
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $fullname, $email, $hashed);
            if ($stmt->execute()) {
                $success = "Registration successful. <a href='login.php'>Login now</a>.";
            } else {
                $errors[] = "Registration failed. Please try again.";
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body {
            background: #f0f2f5;
            font-family: Arial, sans-serif;
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
        }
        .register-box {
            background: white;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            width: 400px;
        }
        .register-box h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color:rgb(42, 59, 157);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color:rgb(36, 33, 134);
        }
        .message {
            text-align: center;
            margin-top: 15px;
        }
        .message a {
            color:rgb(63, 42, 157);
            text-decoration: none;
        }
        .error, .success {
            text-align: center;
            font-size: 14px;
        }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <div class="register-box">
        <h2>Register</h2>
        <?php foreach ($errors as $e) echo "<p class='error'>$e</p>"; ?>
        <?php if ($success) echo "<p class='success'>$success</p>"; ?>
        <form method="POST">
            <input type="text" name="fullname" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm" placeholder="Confirm Password" required>
            <input type="phone" name="phone" placeholder="phone" required>
            <input type="gender" name="gender" placeholder="gender" required>
            
            <button type="submit">Sign Up</button>
        </form>
        <div class="message">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</body>
</html>
