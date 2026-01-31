<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$success = false;
$error = "";
$success_id = "";  // ✅ Move OUTSIDE

if ($_POST) {
    $conn = mysqli_connect("localhost", "root", "", "veltech_db");
    
    if (!$conn) {
        $error = "Database connection failed!";
    } else {
        $college_id = mysqli_real_escape_string($conn, $_POST['college_id']);
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $dob = mysqli_real_escape_string($conn, $_POST['dob']);
        $department = mysqli_real_escape_string($conn, $_POST['department']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        
        // Check duplicate College ID
        $check = mysqli_query($conn, "SELECT college_id FROM students WHERE college_id='$college_id'");
        if (mysqli_num_rows($check) > 0) {
            $error = "❌ College ID '$college_id' already exists!";
        } else {
            $sql = "INSERT INTO students (college_id, name, email, dob, department, phone) 
                    VALUES ('$college_id', '$name', '$email', '$dob', '$department', '$phone')";
            
            if (mysqli_query($conn, $sql)) {
                $success = true;
                $success_id = $college_id;  // ✅ Now accessible
            } else {
                $error = "Insert failed: " . mysqli_error($conn);
            }
        }
        mysqli_close($conn);
    }
}

// ✅ Redirects work perfectly with your form.html
if ($success) {
    header("Location: form.html?success=1&id=" . urlencode($success_id));
} elseif ($error) {
    header("Location: form.html?error=" . urlencode($error));
} else {
    header("Location: form.html");
}
exit();
?>
