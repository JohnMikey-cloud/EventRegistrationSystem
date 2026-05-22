<?php
session_start();

include("../includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit();
}

$query = "
SELECT users.name,
       events.title,
       attendance.status,
       attendance.attendance_date
FROM attendance
JOIN users ON attendance.user_id = users.id
JOIN events ON attendance.event_id = events.id
ORDER BY attendance.id DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>

<head>

<title>Attendance Records</title>

<style>

body{
    font-family:Arial;
    background:#f5f5f5;
}

.container{
    width:900px;
    margin:auto;
    margin-top:50px;
    background:white;
    padding:30px;
    border-radius:10px;
}

h1{
    text-align:center;
    color:orange;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

table, th, td{
    border:1px solid gray;
}

th, td{
    padding:12px;
    text-align:center;
}

a{
    background:orange;
    color:white;
    padding:10px 15px;
    text-decoration:none;
    border-radius:5px;
}

</style>

</head>

<body>

<div class="container">

<h1>Attendance Records</h1>

<table>

<tr>
<th>Student</th>
<th>Event</th>
<th>Status</th>
<th>Date</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['name']; ?></td>

<td><?php echo $row['title']; ?></td>

<td><?php echo $row['status']; ?></td>

<td><?php echo $row['attendance_date']; ?></td>

</tr>

<?php } ?>

</table>

<br>

<a href="dashboard.php">
Back Dashboard
</a>

</div>

</body>
</html>