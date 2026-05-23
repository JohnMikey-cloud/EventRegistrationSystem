<?php
session_start();
include("../../includes/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../../auth/login.php");
    exit();
}

if(isset($_POST['add_event'])){

    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $event_date = $_POST['event_date'];

    $insert = "INSERT INTO events(title, description, event_date)
               VALUES('$title','$description','$event_date')";

    if(mysqli_query($conn, $insert)){
        echo "<script>alert('Event Added Successfully');</script>";
        echo "<script>window.location='admin_events.php';</script>";
    }else{
        echo "<script>alert('Failed to Add Event');</script>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Add Event</title>

<style>

body{
    font-family:Arial;
    background:#f2f2f2;
    margin:0;
    padding:0;
}

.container{
    width:70%;
    margin:auto;
    margin-top:40px;
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

input, textarea{
    width:100%;
    padding:12px;
    margin-top:10px;
    margin-bottom:20px;
    border:1px solid #ccc;
    border-radius:5px;
    font-size:16px;
}

textarea{
    height:120px;
    resize:none;
}

button{
    background:orange;
    color:white;
    border:none;
    padding:12px 25px;
    border-radius:5px;
    font-size:16px;
    cursor:pointer;
}

button:hover{
    background:#cc8400;
}

.back{
    display:inline-block;
    margin-top:20px;
    background:gray;
    color:white;
    padding:10px 20px;
    text-decoration:none;
    border-radius:5px;
}

.back:hover{
    background:#555;
}

</style>

</head>

<body>

<div class="container">

<h1>Add New Event</h1>

<form method="POST">

<input type="text"
name="title"
placeholder="Enter Event Title"
required>

<textarea
name="description"
placeholder="Enter Event Description"
required></textarea>

<input type="date"
name="event_date"
required>

<button type="submit" name="add_event">
Add Event
</button>

</form>

<br>

<a class="back" href="admin_events.php">
Back to Manage Events
</a>

</div>

</body>
</html>