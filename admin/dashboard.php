<?php
session_start();

if($_SESSION['role'] != 'admin'){
    header("Location: ../auth/login.php");
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Admin Dashboard</title>

<style>

body{
    font-family:Arial;
    background:#f5f5f5;
    text-align:center;
}

.box{
    width:500px;
    margin:auto;
    margin-top:80px;
    background:white;
    padding:30px;
    border-radius:10px;
    box-shadow:0px 0px 10px gray;
}

a{
    display:block;
    margin:15px;
    padding:12px;
    background:orange;
    color:white;
    text-decoration:none;
    border-radius:5px;
}

</style>

</head>

<body>

<div class="box">

<h1>Admin Dashboard</h1>

<a href="events/admin_events.php">Manage Events</a>

<a href="../auth/logout.php">Logout</a>

</div>

</body>
</html>