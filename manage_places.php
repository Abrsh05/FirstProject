<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "ethiopia");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle search
$searchTerm = '';
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $searchTerm = $conn->real_escape_string($searchTerm);  // Escape to prevent SQL injection
    $result = $conn->query("SELECT * FROM places WHERE name LIKE '%$searchTerm%' ORDER BY created_at DESC");
} else {
    $result = $conn->query("SELECT * FROM places ORDER BY created_at DESC");
}

// === Delete by ID ===
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);

    // Delete image from uploads folder
    $getImage = $conn->query("SELECT image FROM places WHERE id = $id");
    if ($getImage && $getImage->num_rows > 0) {
        $img = $getImage->fetch_assoc()['image'];
        if (file_exists("uploads/$img")) {
            unlink("uploads/$img");
        }
    }

    // Delete row from DB
    $conn->query("DELETE FROM places WHERE id = $id");
    header("Location: manage_places.php");
    exit();
}

// === Update ===
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_id'])) {
    $id = intval($_POST['update_id']);
    $name = $_POST['name'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $cost = floatval($_POST['cost']);
    $duration = $_POST['duration'];
    
    // Check if a new image is uploaded
    $imageName = null;
    if (!empty($_FILES['image']['name'])) {
        $imageName = basename($_FILES['image']['name']);
        $tmp = $_FILES['image']['tmp_name'];
        $target = "uploads/" . $imageName;

        if (move_uploaded_file($tmp, $target)) {
            // Delete old image
            $old = $conn->query("SELECT image FROM places WHERE id = $id")->fetch_assoc();
            if ($old && file_exists("uploads/" . $old['image'])) {
                unlink("uploads/" . $old['image']);
            }
        } else {
            $imageName = null; // upload failed
        }
    }

    // Update query
    if ($imageName) {
        $stmt = $conn->prepare("UPDATE places SET name=?, location=?, description=?, image=?, cost=?, duration=? WHERE id=?");
        $stmt->bind_param("ssssdsi", $name, $location, $description, $imageName, $cost, $duration, $id);
    } else {
        $stmt = $conn->prepare("UPDATE places SET name=?, location=?, description=?, cost=?, duration=? WHERE id=?");
        $stmt->bind_param("sssddi", $name, $location, $description, $cost, $duration, $id);
    }

    $stmt->execute();
    $stmt->close();
    header("Location: manage_places.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Places</title>
    <style>
        body {
            font-family: Arial;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, td, th {
            border: 1px solid #ccc;
        }
        td, th {
            padding: 10px;
            text-align: left;
        }
        input[type="text"], input[type="number"], textarea {
            width: 100%;
            padding: 6px;
            margin-bottom: 5px;
        }
        button {
            padding: 6px 12px;
            background-color: green;
            color: white;
            border: none;
            border-radius: 4px;
        }
        a {
            margin-left: 10px;
            color: red;
            text-decoration: none;
        }
        img {
            max-width: 100px;
            height: auto;
            display: block;
            margin-bottom: 5px;
        }
        #searchInput {
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            font-size: 16px;
            border: 1px solid #aaa;
            border-radius: 6px;
        }
    </style>
    <script>
        // Image preview
        function previewImage(input, previewId) {
            const file = input.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                document.getElementById(previewId).src = e.target.result;
            };

            if (file) {
                reader.readAsDataURL(file);
            }
        }
    </script>
</head>
<body>

    <h1>Manage Tourist Places</h1>
    <a href="logout.php">Logout</a>

    <h2>Add New Place</h2>
    <form action="add_place.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Name" required><br>
        <input type="text" name="location" placeholder="Location" required><br>
        <textarea name="description" placeholder="Description" required></textarea><br>
        <input type="number" step="0.01" name="cost" placeholder="Cost" required><br>
        <input type="text" name="duration" placeholder="Duration" required><br>
        <input type="file" name="image" required><br>
        <button type="submit">Add Place</button>
    </form>

    <h2>All Places</h2>
    <form method="GET" action="manage_places.php">
        <input type="text" name="search" placeholder="Search places..." value="<?= htmlspecialchars($searchTerm) ?>" />
        <button type="submit">Search</button>
    </form>

    <table>
        <tr>
            <th>Name</th>
            <th>Location</th>
            <th>Description</th>
            <th>Image</th>
            <th>Cost</th>
            <th>Duration</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="update_id" value="<?= $row['id'] ?>">
                <td><input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>"></td>
                <td><input type="text" name="location" value="<?= htmlspecialchars($row['location']) ?>"></td>
                <td><textarea name="description"><?= htmlspecialchars($row['description']) ?></textarea></td>
                <td>
                    <img id="preview_<?= $row['id'] ?>" src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="Image"><br>
                    <input type="file" name="image" onchange="previewImage(this, 'preview_<?= $row['id'] ?>')">
                </td>
                <td><input type="number" step="0.01" name="cost" value="<?= $row['cost'] ?>"></td>
                <td><input type="text" name="duration" value="<?= $row['duration'] ?>"></td>
                <td>
                    <button type="submit">Update</button>
                    <a href="manage_places.php?delete_id=<?= $row['id'] ?>" onclick="return confirm('Delete this place?')">Delete</a>
                </td>
            </form>
        </tr>
        <?php endwhile; ?>
    </table>

</body>
</html>

<?php $conn->close(); ?>
