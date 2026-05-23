<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 🔒 Hard Security Checkpoint: Prevent unauthorized users from viewing analytical data queries
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit();
}

include("../../includes/db.php");

// Fetch analytical stats from database
$total_students_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='student' OR role IS NULL");
$total_data = mysqli_fetch_assoc($total_students_query);
$total_students = $total_data['total'];

$event_breakdown_query = mysqli_query($conn, "
    SELECT events.title, COUNT(attendance.id) as total_signups 
    FROM events 
    LEFT JOIN attendance ON events.id = attendance.event_id 
    GROUP BY events.id 
    ORDER BY total_signups DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Metrics & Queries - Admin Panel</title>
    
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
            max-width: 900px;
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

        .btn-back {
            background: transparent;
            color: #a0aec0;
            border: 1.5px solid #4a5568;
            padding: 10px 18px;
            text-decoration: none;
            font-size: 14px;
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

        .stat-banner {
            background: #f8fafc;
            border-left: 4px solid #ff6600;
            padding: 20px 25px;
            border-radius: 0 12px 12px 0;
            margin-bottom: 35px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #e2e8f0;
            border-right: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
        }

        .stat-text h3 {
            font-size: 0.9rem;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.5px;
        }

        .stat-text p {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1e293b;
            margin-top: 5px;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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

        .badge-count {
            background: #eff6ff;
            color: #2563eb;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            border: 1px solid #bfdbfe;
            display: inline-block;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="workspace-header">
        <h1>Registration Analytics</h1>
        <a class="btn-back" href="../registrants.php">← Roster Sheet</a>
    </div>

    <div class="workspace-body">
        <div class="stat-banner">
            <div class="stat-text">
                <h3>Total Authenticated Students</h3>
                <p><?php echo $total_students; ?> Profiles</p>
            </div>
        </div>

        <h2 class="section-title">Event Registration Densities</h2>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Target Event Identity</th>
                        <th style="width: 25%; text-align: center;">Active Registrants</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($event_breakdown_query)){ ?>
                    <tr>
                        <td style="font-weight: 600; color: #1e293b;"><?php echo htmlspecialchars($row['title']); ?></td>
                        <td style="text-align: center;">
                            <span class="badge-count"><?php echo $row['total_signups']; ?> Attending</span>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>