<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $visit_date = $_POST['visit_date'];

    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "ethiopia");

    // Check for a database connection error
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL query to insert the booking data into the visitor table
    $sql = "INSERT INTO visitor (fullname, phone, origin, destination, visit_date) 
            VALUES (?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the form data to the query
    $stmt->bind_param("sssss", $fullname, $phone, $origin, $destination, $visit_date);

    // Execute the query
    if ($stmt->execute()) {
        // Redirect to the same page after successful submission
        header("Location: dashboard.php");
        exit;
    } else {
        // Display error message if insertion fails
        echo "Error: " . $stmt->error;
    }

    // Close the connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Visitor Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f1f1;
            padding: 20px;
        }

        .dashboard-container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
        }

        button:hover {
            background-color: #0056b3;
        }

        .welcome {
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="welcome">Welcome, <?php echo $_SESSION['user_name']; ?>!</div>
        <h2>Visit Booking Form</h2>
        <form method="POST" action="dashboard.php">
            <input type="text" name="fullname" placeholder="Full Name" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <input type="text" name="origin" placeholder="From" required>
            <input type="text" name="destination" placeholder="To" required>
            <input type="date" name="visit_date" required>
            <button type="submit">Submit Booking</button>
        </form>
    </div>
</body>
</html>
