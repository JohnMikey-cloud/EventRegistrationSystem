<?php
session_start();

include("../includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit();
}

$search = "";

if(isset($_GET['search'])){
    $search = $_GET['search'];
}

$query = "
SELECT users.name,
       users.email,
       events.title
FROM registrations
JOIN users ON registrations.user_id = users.id
JOIN events ON registrations.event_id = events.id
WHERE users.name LIKE '%$search%'
OR users.email LIKE '%$search%'
OR events.title LIKE '%$search%'
ORDER BY registrations.id DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>

<head>

<title>Registrants</title>

<style>

body{
    font-family:Arial;
    background:#f5f5f5;
}

.container{
    width:1000px;
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

input{
    width:100%;
    padding:10px;
    margin-top:10px;
}

button{
    padding:10px 20px;
    background:orange;
    color:white;
    border:none;
    margin-top:10px;
    cursor:pointer;
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

<h1>Student Registrants</h1>

<form method="GET">

<input
type="text"
name="search"
placeholder="Search Student / Email / Event"
value="<?php echo $search; ?>"
>

<button type="submit">
Search
</button>

</form>

<table>

<tr>
<th>Student Name</th>
<th>Email</th>
<th>Event</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['name']; ?></td>

<td><?php echo $row['email']; ?></td>

<td><?php echo $row['title']; ?></td>

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
