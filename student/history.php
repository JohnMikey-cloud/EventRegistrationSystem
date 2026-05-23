<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = mysqli_query($conn, "
SELECT events.title, events.description, events.event_date
FROM registrations
INNER JOIN events ON registrations.event_id = events.id
WHERE registrations.user_id='$user_id'
ORDER BY events.event_date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>My Event History</title>

<style>

body{
    font-family:Arial;
    background:#f5f5f5;
}

.container{
    width:80%;
    margin:auto;
    margin-top:50px;
    background:white;
    padding:30px;
    border-radius:10px;
    box-shadow:0px 0px 10px gray;
}

h1{
    text-align:center;
    color:orange;
    margin-bottom:30px;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th, table td{
    border:1px solid gray;
    padding:15px;
    text-align:center;
}

th{
    background:orange;
    color:white;
}

.back{
    display:inline-block;
    margin-top:20px;
    padding:12px 20px;
    background:gray;
    color:white;
    text-decoration:none;
    border-radius:5px;
}

.back:hover{
    background:black;
}

.no-data{
    text-align:center;
    padding:20px;
    color:red;
    font-size:18px;
}

</style>
</head>

<body>

<div class="container">

<h1>My Event History</h1>

<table>

<tr>
    <th>Event Title</th>
    <th>Description</th>
    <th>Date</th>
</tr>

<?php

if(mysqli_num_rows($query) > 0){

    while($row = mysqli_fetch_assoc($query)){

?>

<tr>
    <td><?php echo $row['title']; ?></td>
    <td><?php echo $row['description']; ?></td>
    <td><?php echo $row['event_date']; ?></td>
</tr>

<?php
    }

}else{
?>

<tr>
    <td colspan="3" class="no-data">
        No Event History Found
    </td>
</tr>

<?php } ?>

</table>

<a class="back" href="dashboard.php">
Back to Dashboard
</a>

</div>

</body>
</html>