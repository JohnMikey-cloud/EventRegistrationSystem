<?php
session_start();
include("../../includes/db.php");

if(!isset($_GET['id'])){
    die("No Event ID Found");
}

$id = $_GET['id'];

$query = "SELECT * FROM events WHERE id='$id'";
$result = mysqli_query($conn, $query);

$event = mysqli_fetch_assoc($result);

if(!$event){
    die("Event not found");
}

if(isset($_POST['update'])){

    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];

    $update = "UPDATE events 
               SET title='$title',
                   description='$description',
                   event_date='$date'
               WHERE id='$id'";

    mysqli_query($conn, $update);

    header("Location: admin_events.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Edit Event</title>

<style>

body{
    font-family:Arial;
    background:#f5f5f5;
}

.container{
    width:500px;
    margin:auto;
    margin-top:50px;
    background:white;
    padding:30px;
    border-radius:10px;
    box-shadow:0px 0px 10px gray;
}

input, textarea{
    width:100%;
    padding:12px;
    margin-top:10px;
    margin-bottom:20px;
}

button{
    padding:12px 20px;
    background:orange;
    color:white;
    border:none;
    cursor:pointer;
}

button:hover{
    background:darkorange;
}

</style>

</head>

<body>

<div class="container">

<h2>Edit Event</h2>

<form method="POST">

<label>Event Title</label>

<input type="text"
name="title"
value="<?php echo $event['title']; ?>"
required>

<label>Description</label>

<textarea name="description" required><?php echo $event['description']; ?></textarea>

<label>Event Date</label>

<input type="date"
name="date"
value="<?php echo $event['event_date']; ?>"
required>

<button type="submit" name="update">
Update Event
</button>

</form>

</div>

</body>
</html>