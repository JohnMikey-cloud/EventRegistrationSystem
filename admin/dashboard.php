<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 🔑 THE SECURITY OVERRIDE KEY
$secret_developer_key = "admin123"; 

// Check if you are accessing via the URL key OR if you are already verified
if (isset($_GET['developer_key']) && $_GET['developer_key'] === $secret_developer_key) {
    $_SESSION['role'] = 'admin';
    $_SESSION['name'] = 'Database Owner';
}

// 🛡️ THE BACKUP FIX: If you are already logged in as admin, keep your session alive!
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    // Session is safe and verified. Do nothing and allow the page to load.
    if (!isset($_SESSION['name'])) {
        $_SESSION['name'] = 'Database Owner';
    }
} else {
    // If you don't pass the key AND you don't have an active admin session, boot to login
    header("Location: ../auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Control Panel</title>
    
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #0b0c10;
            background-image: linear-gradient(135px, #111215 0%, #1c1e22 100%);
            min-height: 100vh;
            padding: 40px 20px;
            color: #ffffff;
        }

        .dashboard-container {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
        }

        .dashboard-header {
            background-color: #212529;
            padding: 30px;
            border-radius: 16px;
            border-bottom: 4px solid #ff6600;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            margin-bottom: 30px;
        }

        .header-title h1 {
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .header-title p {
            color: #a0aec0;
            font-size: 14px;
            margin-top: 4px;
        }

        .btn-logout {
            padding: 10px 20px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            border-radius: 8px;
            background: transparent;
            color: #dc3545;
            border: 2px solid #dc3545;
            transition: all 0.2s ease;
        }

        .btn-logout:hover {
            background: #dc3545;
            color: #ffffff;
        }

        .grid-workspace {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .action-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            border: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.2s ease;
        }

        .action-card:hover {
            transform: translateY(-4px);
        }

        .card-meta h3 {
            color: #212529;
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .card-meta p {
            color: #64748b;
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 25px;
        }

        .btn-action {
            display: block;
            width: 100%;
            padding: 12px;
            text-align: center;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            border-radius: 8px;
            background: #ff6600;
            color: #ffffff;
            transition: background 0.2s ease;
        }

        .btn-action:hover {
            background: #cc5200;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    
    <div class="dashboard-header">
        <div class="header-title">
            <h1>Admin Management Console</h1>
            <p>System Privileges: <?php echo htmlspecialchars($_SESSION['name']); ?></p>
        </div>
        <a href="../auth/logout.php" class="btn-logout">Secure Logout</a>
    </div>

    <div class="grid-workspace">
        
        <div class="action-card">
            <div class="card-meta">
                <h3>Events Manifest</h3>
                <p>Create, update scheduling arrays, or delete live student-facing records.</p>
            </div>
            <a href="events/admin_events.php" class="btn-action">Manage Events</a>
        </div>

        <div class="action-card">
            <div class="card-meta">
                <h3>Student Roster</h3>
                <p>Monitor student portal directory accounts and check database assignments.</p>
            </div>
            <a href="registrants.php" class="btn-action">View Registrants</a>
        </div>

        <div class="action-card">
            <div class="card-meta">
                <h3>Attendance Sheets</h3>
                <p>Generate, clear metrics tracking logs, or print complete registration summaries.</p>
            </div>
            <a href="print_attendance.php" class="btn-action">Print Logs</a>
        </div>

    </div>
</div>

</body>
</html>