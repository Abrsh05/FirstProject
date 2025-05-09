<?php
// Define variables and initialize with empty values
$name = $email = $gender = $message = "";
$nameErr = $emailErr = $genderErr = $messageErr = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate Name (Required & Only Letters)
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $nameErr = "Only letters and spaces allowed";
        }
    }
    // Validate Email (Required & Valid Format)
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }
    // Validate Gender (Required)
    if (empty($_POST["gender"])) {
        $genderErr = "Gender is required";
    } else {
        $gender = test_input($_POST["gender"]);
    }
    // Validate Message (Optional, But Limit Characters)
    if (!empty($_POST["message"])) {
        $message = test_input($_POST["message"]);
        if (strlen($message) > 250) {
            $messageErr = "Message should not exceed 250 characters";
        }
    }
}
// Function to sanitize input
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>PHP Complete Form Validation</title>
</head>

<body>
    <h2>PHP Complete Form Validation</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Name: <input type="text" name="name" value="<?php echo $name; ?>">
        <span style="color: red;">* <?php echo $nameErr; ?></span><br><br>
        Email: <input type="text" name="email" value="<?php echo $email; ?>">
        <span style="color: red;">* <?php echo $emailErr; ?></span><br><br>
        Gender:
        <input type="radio" name="gender" value="Male" <?php if ($gender == "Male") echo "checked"; ?>> Male
        <input type="radio" name="gender" value="Female" <?php if ($gender == "Female") echo "checked"; ?>> Female
        <span style="color: red;">* <?php echo $genderErr; ?></span><br><br>
        Message: <textarea name="message" rows="4" cols="40"><?php echo $message; ?></textarea>
        <span style="color: red;"><?php echo $messageErr; ?></span><br><br>
        <input type="submit" name="submit" value="Submit">
    </form>
    <?php
    // Display Success Message if No Errors
    if ($_SERVER["REQUEST_METHOD"] == "POST" && $nameErr == "" && $emailErr == "" && $genderErr == "" && $messageErr == "") {
        echo "<h3>Form Submitted Successfully</h3>";
        echo "Name: " . $name . "<br>";
        echo "Email: " . $email . "<br>";
        echo "Gender: " . $gender . "<br>";
        echo "Message: " . $message . "<br>";
    }
    ?>
</body>

</html>