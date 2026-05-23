<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 🔒 Hard Security Checkpoint: Prevent unauthorized visitors from viewing the student roster
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

include("../includes/db.php");

// Fetch all registered students from the database
$query = "SELECT id, name, email FROM users WHERE role = 'student' OR role IS NULL ORDER BY id DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    $query = "SELECT id, name, email FROM users ORDER BY id DESC";
    $result = mysqli_query($conn, $query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Registrants - Admin Panel</title>
    
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
            color: #212529;
        }

        .container {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 102, 0, 0.05);
        }

        .workspace-header {
            background-color: #212529;
            padding: 35px 30px;
            color: #ffffff;
            border-bottom: 4px solid #ff6600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .workspace-header h1 {
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* Group alignment for buttons */
        .header-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .btn-nav-orange {
            background-color: #ff6600;
            color: #ffffff;
            padding: 10px 16px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            border-radius: 8px;
            transition: background 0.2s ease;
            border: none;
        }

        .btn-nav-orange:hover {
            background-color: #cc5200;
        }

        .btn-nav-gray {
            background-color: #4a5568;
            color: #ffffff;
            padding: 10px 16px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            border-radius: 8px;
            transition: background 0.2s ease;
            border: none;
        }

        .btn-nav-gray:hover {
            background-color: #333b48;
        }

        .btn-back {
            background: transparent;
            color: #a0aec0;
            border: 1.5px solid #4a5568;
            padding: 9px 16px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .btn-back:hover {
            background: #4a5568;
            color: #ffffff;
        }

        .workspace-body {
            padding: 40px;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
        }

        th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #cbd5e1;
            padding: 16px;
            text-align: left;
        }

        td {
            padding: 16px;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
            text-align: left;
        }

        tr:hover td {
            background-color: #f8fafc;
        }

        th:nth-child(1), td:nth-child(1) { width: 12%; text-align: center; }
        th:nth-child(2), td:nth-child(2) { width: 44%; font-weight: 600; color: #1e293b; }
        th:nth-child(3), td:nth-child(3) { width: 44%; color: #475569; }
    </style>
</head>
<body>

<div class="container">
    <div class="workspace-header">
        <h1>Student Registration Roster</h1>
        
        <div class="header-actions">
            <a class="btn-nav-orange" href="registrants/registrants_filter.php">🔍 Filter By Event</a>
            <a class="btn-nav-gray" href="registrants/queries.php">📊 Analytics Metrics</a>
            <a class="btn-back" href="dashboard.php">← Dashboard</a>
        </div>
    </div>

    <div class="workspace-body">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="text-align: center;">User ID</th>
                        <th>Full Legal Name</th>
                        <th>Email Address Workspace</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)){ ?>
                    <tr>
                        <td style="text-align: center; font-weight: 600; color: #64748b;"><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                    </tr>
                    <?php } ?>
                    
                    <?php if(mysqli_num_rows($result) === 0): ?>
                    <tr>
                        <td colspan="3" style="text-align: center; color: #94a3b8; padding: 40px; font-style: italic;">
                            No standard student portal accounts have been registered in the database system yet.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>