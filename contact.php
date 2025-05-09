<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Contact Us - Explore Ethiopia</title>
  <style>
    /* ===== Reset and Base Styles ===== */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      color: #333;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* ===== Full-Width Header ===== */
    header {
      width: 100%;
      background-color: #0b3d91;
      color: white;
      padding: 10px 0;
    }

    header .header-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    header h1 {
      font-size: 2rem;
      margin-bottom: 10px;
    }

    /* ===== Navbar Styles ===== */
    nav {
      width: 100%;
      background-color: #0b3d91;
    }

    nav ul {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      list-style: none;
      padding: 0;
      margin: 0;
    }

    nav ul li {
      margin: 5px 15px;
    }

    nav ul li a {
      color: white;
      text-decoration: none;
      font-weight: bold;
      padding: 8px 12px;
      border-radius: 4px;
      transition: background-color 0.3s;
    }

    nav ul li a:hover,
    nav ul li a.active {
      background-color: #062b6e;
    }

    /* ===== Page Content ===== */
    .content-wrapper {
      flex: 1;
      max-width: 800px;
      margin: 20px auto;
      padding: 20px;
      background: white;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    /* ===== Contact Section ===== */
    .contact h2 {
      color: #0b3d91;
      text-align: center;
      margin-bottom: 10px;
    }

    .contact p {
      text-align: center;
      margin-bottom: 20px;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    form input,
    form textarea {
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 1rem;
    }

    form button {
      padding: 12px;
      background-color: #0b3d91;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 1rem;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    form button:hover {
      background-color: #062b6e;
    }

    .success-message {
      color: green;
      font-weight: bold;
      text-align: center;
    }

    /* ===== Fixed Footer ===== */
    footer {
      background-color: #0b3d91;
      color: white;
      text-align: center;
      padding: 15px 0;
      position: fixed;
      bottom: 0;
      width: 100%;
    }
  </style>
</head>
<body>

  <!-- Page Wrapper -->
  <div class="content-wrapper">
    <!-- Header -->
    <header>
      <div class="header-container">
        <h1>Ethiopia</h1>
        <nav>
          <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="about.html">About</a></li>
            <li><a href="place.html">Places</a></li>
            <li><a href="gallery.html">Gallery</a></li>
            <li><a href="contact.php" class="active">Contact</a></li>
            <li><a href="admin_login.php">Admin</a></li>
            <li><a href="register.php">User</a></li>

            
          </ul>
        </nav>
      </div>
    </header>

    <!-- 💬 Contact Section -->
    <section class="contact">
      <h2>Contact Us</h2>
      <p>We'd love to hear from you!</p>

      <?php
      $success = false;

      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $conn = new mysqli("localhost", "root", "", "ethiopia");

        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }

        $name = $_POST['name'];
        $email = $_POST['email'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];

        $stmt = $conn->prepare("INSERT INTO contact (name, email, subject, message) VALUES (?, ?, ?, ?)");

        if ($stmt) {
          $stmt->bind_param("ssss", $name, $email, $subject, $message);
          if ($stmt->execute()) {
            $success = true;
          }
          $stmt->close();
        } else {
          echo "Error: " . $conn->error;
        }

        $conn->close();
      }

      if ($success) {
        echo "<p class='success-message'></p>";
      }
      ?>

      <form method="POST" action="contact.php">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="email" name="email" placeholder="Your Email" required>
        <input type="text" name="subject" placeholder="Your Subject" required>
        <textarea name="message" placeholder="Your Message" rows="5" required></textarea>
        <button type="submit">Send Message</button>
      </form>
    </section>
  </div>

  <footer>
    <p>&copy; 2025 Explore Ethiopia. All rights reserved.</p>
  </footer>

</body>
</html>
