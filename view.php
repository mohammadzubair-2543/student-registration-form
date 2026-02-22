<?php
$conn = mysqli_connect("localhost", "root", "", "veltech_db");
$sql = "SELECT * FROM students ORDER BY college_id DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Students</title>
    <link rel="stylesheet" href="form.css">
    <style>
        .table-container { max-width: 1200px; margin: 20px auto; background: rgba(255,255,255,0.95); padding: 30px; border-radius: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #4CAF50; color: white; }
        tr:hover { background: #f5f5f5; }
        .college-id { font-weight: bold; color: #2196F3; background: #e3f2fd; padding: 6px 12px; border-radius: 6px; }
    </style>
</head>
<body>
    <div class="table-container">
        <h1>📋 All Students (<?php echo mysqli_num_rows($result); ?>)</h1>
        <a href="form.php" class="view-link">➕ New Registration</a>
        <table>
            <tr><th>College ID</th><th>Name</th><th>Email</th><th>DOB</th><th>Department</th><th>Phone</th><th>Registered</th></tr>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><span class="college-id"><?php echo htmlspecialchars($row['college_id']); ?></span></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo $row['dob']; ?></td>
                <td><?php echo $row['department']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td><?php echo $row['created_at']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>
