<?php
$conn = new mysqli("localhost", "root", "", "veltech_db");
if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}

$sort = $_GET['sort'] ?? 'name';
$filter = $_GET['dept'] ?? '';
$order = $_GET['order'] ?? 'ASC';

$total_result = $conn->query("SELECT COUNT(*) as c FROM students");
$total = $total_result->fetch_assoc()['c'];

$depts_result = $conn->query("SELECT department, COUNT(*) as c FROM students GROUP BY department");

$sql = "SELECT * FROM students WHERE 1=1";
if ($filter) $sql .= " AND department='$filter'";
$sql .= " ORDER BY $sort $order";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <link href="form.css" rel="stylesheet">
    <style>
        .dashboard-main {
            min-height: 100vh;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }
        
        .stats-row {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .stat-box {
            background: rgba(240, 4, 4, 0.1);
            backdrop-filter: blur(10px);
            padding: 1rem 1.5rem;
            border-radius: 15px;
            color: white;
            min-width: 120px;
            text-align: center;
            font-weight: 500;
        }
        
        .controls-row {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            padding: 1rem;
        }
        
        .control-select {
            padding: 0.6rem 0.8rem;  
            background: rgba(223, 15, 15, 0.2);
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 1.1rem;    
            cursor: pointer;
            min-width: 160px;     
        }
        
        .table-container {
            background: rgba(5, 242, 80, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            overflow: hidden;
            margin-top: 1rem;
        }
        
        .students-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .students-table th {
            background: rgba(160, 33, 33, 0.2);
            padding: 1rem;
            color: white;
            text-align: left;
        }
        
        .students-table td {
            padding: 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            color: white;
        }
        
        .students-table tr:hover td {
            background: rgba(255,255,255,0.1);
        }
    </style>
</head>
<body>
    <div class="dashboard-main">
        
        <div class="stats-row">
            <div class="stat-box">Total: <?php echo $total; ?></div>
            <?php while($d = $depts_result->fetch_assoc()): ?>
                <div class="stat-box"><?php echo $d['department'] . ": " . $d['c']; ?></div>
            <?php endwhile; ?>
        </div>

        <div class="controls-row">
            <select class="control-select" onchange="location='?sort=name&order=ASC'">
                <option <?php echo ($sort=='name'&&$order=='ASC')?'selected':'';?>>Name A-Z</option>
                <option <?php echo ($sort=='name'&&$order=='DESC')?'selected':'';?>>Name Z-A</option>
                <option <?php echo ($sort=='reg_date'&&$order=='DESC')?'selected':'';?>>Date New</option>
                <option <?php echo ($sort=='reg_date'&&$order=='ASC')?'selected':'';?>>Date Old</option>
            </select>
            
            <select class="control-select" onchange="location=(this.value=='All')?'?':'?dept='+this.value">
                <option <?php echo (!$filter)?'selected':'';?>>All Depts</option>
                <option <?php echo ($filter=='CSE')?'selected':'';?>>CSE</option>
                <option <?php echo ($filter=='ECE')?'selected':'';?>>ECE</option>
                <option <?php echo ($filter=='MECH')?'selected':'';?>>MECH</option>
            </select>
        </div>

        <div class="table-container">
            <table class="students-table">
                <tr>
                    <th>Name</th>
                    <th>College ID</th>
                    <th>Department</th>
                    <th>Date</th>
                </tr>
                <?php 
                if ($result && $result->num_rows > 0) {
                    $result->data_seek(0);
                    while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['college_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['department']); ?></td>
                        <td><?php echo date('d-m-Y', strtotime($row['reg_date'])); ?></td>
                    </tr>
                    <?php endwhile;
                } else { ?>
                    <tr><td colspan="4" style="text-align:center;color:#ccc;">No students found</td></tr>
                <?php } ?>
            </table>
        </div>
    </div>
</body>
</html>
