<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $conn = mysqli_connect("localhost", "root", "", "veltech_db");

    if (!$conn) {
        $_SESSION['error'] = "Database connection failed!";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    $college_id = mysqli_real_escape_string($conn, $_POST['college_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "❌ Invalid Email Format!";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Check duplicate College ID or Email
    $check = mysqli_query($conn, 
        "SELECT college_id, email FROM students 
         WHERE college_id='$college_id' OR email='$email'"
    );

    if (mysqli_num_rows($check) > 0) {

        $row = mysqli_fetch_assoc($check);

        if ($row['college_id'] == $college_id) {
            $_SESSION['error'] = "❌ College ID '$college_id' already exists!";
        } elseif ($row['email'] == $email) {
            $_SESSION['error'] = "❌ Email '$email' is already registered!";
        }

        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Insert student
    $sql = "INSERT INTO students (college_id, name, email, dob, department, phone)
            VALUES ('$college_id', '$name', '$email', '$dob', '$department', '$phone')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "✅ Student Registered Successfully!";
    } else {
        $_SESSION['error'] = "Insert failed: " . mysqli_error($conn);
    }

    mysqli_close($conn);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Registration - Veltech</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="form.css">
</head>
<body>

<div class="form-container">

    <h1>🎓 Student Registration</h1>

    <!-- Success Message -->
    <?php if(isset($_SESSION['success'])): ?>
        <p style="color:green; font-weight:bold; text-align:center; margin-bottom:15px;">
            <?php 
                echo $_SESSION['success']; 
                unset($_SESSION['success']);
            ?>
        </p>
    <?php endif; ?>

    <!-- Error Message -->
    <?php if(isset($_SESSION['error'])): ?>
        <p style="color:red; font-weight:bold; text-align:center; margin-bottom:15px;">
            <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']);
            ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <div class="form-group">
            <label>College ID <span class="required">*</span></label>
            <input type="text" name="college_id" placeholder="VTU2025001" required>
        </div>

        <div class="form-group">
            <label>Full Name <span class="required">*</span></label>
            <input type="text" name="name" required>
        </div>

        <div class="form-group">
            <label>Email <span class="required">*</span></label>
            <input type="email" name="email" required>
        </div>

        <div class="form-group">
            <label>Date of Birth <span class="required">*</span></label>
            <input type="date" name="dob" required>
        </div>

        <div class="form-group">
            <label>Department <span class="required">*</span></label>
            <select name="department" required>
                <option value="">Select Department</option>
                <option value="CSE">Computer Science (CSE)</option>
                <option value="ECE">Electronics (ECE)</option>
                <option value="MECH">Mechanical</option>
                <option value="CIVIL">Civil</option>
            </select>
        </div>

        <div class="form-group">
            <label>Phone <span class="required">*</span></label>
            <input type="tel" name="phone" pattern="[0-9]{10}" required>
        </div>

        <button type="submit">Register Student</button>

    </form>

    <a href="view.php" class="view-link">📋 View All Students</a>

</div>

</body>
</html>