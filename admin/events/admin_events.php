<?php
include("../../includes/db.php");

if(isset($_POST['add_event'])){

    $title = $_POST['title'];
    $description = $_POST['description'];
    $event_date = $_POST['event_date'];

    $insert = "INSERT INTO events(title, description, event_date)
               VALUES('$title','$description','$event_date')";

    mysqli_query($conn, $insert);
}

$events = mysqli_query($conn, "SELECT * FROM events ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>

<title>Manage Events</title>

<style>

body{
    font-family:Arial;
    background:#f2f2f2;
}

.container{
    width:90%;
    margin:auto;
    background:white;
    padding:20px;
    border-radius:10px;
}

h1{
    text-align:center;
    color:orange;
}

input, textarea{
    width:100%;
    padding:12px;
    margin-top:10px;
    margin-bottom:15px;
}

button{
    background:orange;
    color:white;
    border:none;
    padding:12px 25px;
    cursor:pointer;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:30px;
}

table, th, td{
    border:1px solid gray;
}

th, td{
    padding:15px;
    text-align:center;
}

.back{
    background:gray;
    color:white;
    padding:12px 20px;
    text-decoration:none;
    border-radius:5px;
}

.action-buttons{
    display:flex;
    justify-content:center;
    gap:10px;
}

.edit-btn{
    background:blue;
    color:white;
    padding:8px 15px;
    text-decoration:none;
    border-radius:5px;
}

.delete-btn{
    background:red;
    color:white;
    padding:8px 15px;
    text-decoration:none;
    border-radius:5px;
}

</style>

</head>

<body>

<div class="container">

<h1>Manage Events</h1>

<form method="POST">

<input type="text" name="title" placeholder="Event Title" required>

<textarea name="description" placeholder="Event Description" required></textarea>

<input type="date" name="event_date" required>

<button type="submit" name="add_event">
Add Event
</button>

</form>

<table>

<tr>
    <th>ID</th>
    <th>Title</th>
    <th>Description</th>
    <th>Date</th>
    <th>Actions</th>
</tr>

<?php while($row = mysqli_fetch_assoc($events)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['title']; ?></td>

<td><?php echo $row['description']; ?></td>

<td><?php echo $row['event_date']; ?></td>

<td>

<div class="action-buttons">

<a class="edit-btn"
href="admin_edit_event.php?id=<?php echo $row['id']; ?>">
Edit
</a>

<a class="delete-btn"
href="admin_delete_event.php?id=<?php echo $row['id']; ?>"
onclick="return confirm('Delete this event?')">
Delete
</a>

</div>

</td>

</tr>

<?php } ?>

</table>

<br>

<a class="back" href="../dashboard.php">
Back Dashboard
</a>

</div>

</body>
</html>