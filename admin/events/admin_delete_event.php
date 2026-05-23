<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 🔒 Hard Security Checkpoint: Prevent malicious URLs from deleting events
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit();
}

// 🛠️ Fixed Include Path: Adjusted to look up two directories into your shared folders
include("../../includes/db.php");

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Perform Delete Query
    mysqli_query($conn, "DELETE FROM events WHERE id='$id'");
}

// 🛠️ Fixed Redirection Path: Safely bounce back to your modern dashboard view file
header("Location: admin_events.php");
exit();
?>