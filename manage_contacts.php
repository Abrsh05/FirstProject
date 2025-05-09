<?php
session_start();
$conn = new mysqli("localhost", "root", "", "ethiopia");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete message
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM contact WHERE id = $id");
    header("Location: manage_contact.php");
    exit();
}

// Reply message (simulated)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reply_id'])) {
    $to = $_POST['email'];
    $message = $_POST['reply_message'];
    // You can integrate PHPMailer here to send real emails
    echo "<script>alert('Reply sent to $to (simulated)');</script>";
}

// Fetch messages
$messages = $conn->query("SELECT * FROM contact ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Contact Messages</title>
    <style>
        body {
            font-family: Arial;
            padding: 20px;
        }
        .message-box {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .btn {
            padding: 8px 16px;
            margin-right: 8px;
            border: none;
            border-radius: 6px;
            color: white;
            cursor: pointer;
        }
        .display-btn { background-color: #007bff; }
        .delete-btn { background-color: #dc3545; }
        .reply-btn  { background-color: #28a745; }
        textarea {
            width: 100%;
            padding: 8px;
            margin-top: 8px;
        }
    </style>
</head>
<body>

    <h1>Manage Contact Messages</h1>

    <?php while ($row = $messages->fetch_assoc()): ?>
        <div class="message-box">
            <p><strong>Name:</strong> <?= htmlspecialchars($row['name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>
            <p><strong>Message:</strong> <?= htmlspecialchars($row['message']) ?></p>
            
            <a href="?delete_id=<?= $row['id'] ?>" class="btn delete-btn" onclick="return confirm('Delete this message?')">Delete</a>

            <form method="POST" style="margin-top:10px;">
                <input type="hidden" name="reply_id" value="<?= $row['id'] ?>">
                <input type="hidden" name="email" value="<?= htmlspecialchars($row['email']) ?>">
                <textarea name="reply_message" placeholder="Write your reply..." required></textarea>
                <button type="submit" class="btn reply-btn">Reply</button>
            </form>
        </div>
    <?php endwhile; ?>

</body>
</html>

<?php $conn->close(); ?>
