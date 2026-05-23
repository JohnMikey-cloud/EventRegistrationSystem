<?php
session_start();

include("../includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit();
}

/* MARK ATTENDANCE */

if(isset($_GET['user_id']) && isset($_GET['event_id'])){

    $user_id = $_GET['user_id'];
    $event_id = $_GET['event_id'];

    // CHECK DUPLICATE
    $check = mysqli_query($conn,"
        SELECT * FROM attendance
        WHERE user_id='$user_id'
        AND event_id='$event_id'
    ");

    if(mysqli_num_rows($check) == 0){

        mysqli_query($conn,"
            INSERT INTO attendance(user_id,event_id)
            VALUES('$user_id','$event_id')
        ");

        echo "
        <script>
            alert('Attendance Marked!');
            window.location='attendance.php';
        </script>
        ";

    }else{

        echo "
        <script>
            alert('Already Marked Present!');
            window.location='attendance.php';
        </script>
        ";
    }
}

/* GET REGISTRATIONS */

$query = "
SELECT registrations.user_id,
       users.name,
       events.title,
       events.id AS event_id
FROM registrations
JOIN users ON registrations.user_id = users.id
JOIN events ON registrations.event_id = events.id
ORDER BY registrations.id DESC
";

$result = mysqli_query($conn,$query);
?>

<!DOCTYPE html>
<html>

<head>

<title>Attendance System</title>

<style>

body{
    font-family:Arial;
    background:#f5f5f5;
    margin:0;
    padding:0;
}

.container{
    width:900px;
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
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

table,th,td{
    border:1px solid gray;
}

th,td{
    padding:12px;
    text-align:center;
}

a{
    background:green;
    color:white;
    padding:8px 12px;
    text-decoration:none;
    border-radius:5px;
}

a:hover{
    background:darkgreen;
}

.back{
    display:inline-block;
    margin-top:20px;
    background:orange;
}

.back:hover{
    background:#cc6600;
}

</style>

</head>

<body>

<div class="container">

<h1>Attendance System</h1>

<table>

<tr>
<th>Student</th>
<th>Event</th>
<th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['name']; ?></td>

<td><?php echo $row['title']; ?></td>

<td>

<a href="?user_id=<?php echo $row['user_id']; ?>&event_id=<?php echo $row['event_id']; ?>">
Mark Present
</a>

</td>

</tr>

<?php } ?>

</table>

<br>

<a class="back" href="dashboard.php">
Back Dashboard
</a>

</div>

</body>
</html>