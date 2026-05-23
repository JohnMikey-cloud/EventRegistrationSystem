<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 🔒 Hard Security Checkpoint: Prevent unauthorized visitors from exporting logs
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

include("../includes/db.php");

$query = "
SELECT users.name,
       events.title
FROM attendance
JOIN users ON attendance.user_id = users.id
JOIN events ON attendance.event_id = events.id
ORDER BY attendance.id DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Attendance - Admin Panel</title>
    
    <style>
        /* Base Global Reset */
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

        /* Large Workspace Container */
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

        /* Top Action Menu Panel Row */
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

        .action-links {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Upgraded Control Panel Buttons */
        .btn-print {
            background-color: #ff6600;
            color: #ffffff;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .btn-print:hover {
            background-color: #cc5200;
        }

        .btn-back {
            background: transparent;
            color: #a0aec0;
            border: 1.5px solid #4a5568;
            padding: 9px 18px;
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

        /* Form Content Workspace Padding */
        .workspace-body {
            padding: 40px;
        }

        /* Clean Modern Data Table CSS */
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

        /* Layout distribution sizes for structural columns */
        th:nth-child(1), td:nth-child(1) { width: 40%; font-weight: 600; color: #1e293b; }
        th:nth-child(2), td:nth-child(2) { width: 60%; color: #475569; }

        /* 🖨️ SMART PRINTER OPTIMIZATION MATRIX */
        @media print {
            body {
                background: #ffffff !important;
                background-image: none !important;
                padding: 0 !important;
                color: #000000 !important;
            }

            .container {
                box-shadow: none !important;
                border: none !important;
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .workspace-header {
                background: transparent !important;
                color: #000000 !important;
                border-bottom: 2px solid #000000 !important;
                padding: 20px 0 !important;
                display: block !important;
                text-align: center;
            }

            .workspace-header h1 {
                font-size: 2rem !important;
                color: #000000 !important;
            }

            .action-links {
                display: none !important; /* Fully strips out navigation links & print triggers from the page layout */
            }

            .workspace-body {
                padding: 20px 0 !important;
            }

            table {
                width: 100% !important;
                border: 1px solid #000000 !important;
            }

            th {
                background: #e2e8f0 !important;
                color: #000000 !important;
                border-bottom: 2px solid #000000 !important;
                padding: 12px !important;
            }

            td {
                border-bottom: 1px solid #000000 !important;
                padding: 12px !important;
                color: #000000 !important;
            }
        }
    </style>
</head>

<body>

<div class="container">
    <div class="workspace-header">
        <h1>Attendance Tracking Logs</h1>
        <div class="action-links">
            <button onclick="window.print()" class="btn-print">Print Sheet</button>
            <a class="btn-back" href="dashboard.php">Dashboard</a>
        </div>
    </div>

    <div class="workspace-body">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Registered Event Event</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)){ ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                    </tr>
                    <?php } ?>
                    <?php if(mysqli_num_rows($result) === 0): ?>
                    <tr>
                        <td colspan="2" style="text-align: center; color: #94a3b8; padding: 40px; font-style: italic;">
                            No active student attendance registration parameters logged in database yet.
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