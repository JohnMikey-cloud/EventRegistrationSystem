<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 🔒 Hard Security Checkpoint: Prevent unauthorized access to registrant filters
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit();
}

include("../../includes/db.php");

// 1. Fetch all events from the database to populate our selection dropdown menu
$events_query = "SELECT id, title FROM events ORDER BY title ASC";
$events_result = mysqli_query($conn, $events_query);

// 2. Track which event ID the admin is filtering by
$selected_event_id = isset($_GET['event_id']) ? mysqli_real_escape_string($conn, $_GET['event_id']) : 'all';

// 3. Build the conditional query depending on whether "All Events" or a specific one is selected
if ($selected_event_id === 'all' || $selected_event_id === '') {
    $students_query = "
        SELECT users.name, users.email, events.title AS event_title 
        FROM attendance
        JOIN users ON attendance.user_id = users.id
        JOIN events ON attendance.event_id = events.id
        ORDER BY attendance.id DESC
    ";
} else {
    $students_query = "
        SELECT users.name, users.email, events.title AS event_title 
        FROM attendance
        JOIN users ON attendance.user_id = users.id
        JOIN events ON attendance.event_id = events.id
        WHERE attendance.event_id = '$selected_event_id'
        ORDER BY attendance.id DESC
    ";
}

$students_result = mysqli_query($conn, $students_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filter Registrants - Admin Panel</title>
    
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
            background-image: linear-gradient(135px, #111215 0%, #1c1e22 100__);
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

        /* Unified Navigation Header */
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

        /* Form Content Workspace Padding */
        .workspace-body {
            padding: 40px;
        }

        /* Interactive Filter Bar Component Box */
        .filter-section {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .filter-label {
            font-weight: 700;
            color: #4a5568;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Modernized Dropdown Selector Controls */
        .select-input {
            padding: 10px 16px;
            border-radius: 8px;
            border: 1.5px solid #ced4da;
            font-size: 0.95rem;
            background-color: #ffffff;
            color: #212529;
            outline: none;
            min-width: 250px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .select-input:focus {
            border-color: #ff6600;
            box-shadow: 0 0 0 3px rgba(255, 102, 0, 0.15);
        }

        /* Data Tables styling matrix */
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

        /* Column width ratios definitions */
        th:nth-child(1), td:nth-child(1) { width: 35%; font-weight: 600; color: #1e293b; }
        th:nth-child(2), td:nth-child(2) { width: 35%; color: #475569; }
        th:nth-child(3), td:nth-child(3) { width: 30%; color: #ff6600; font-weight: 600; }
    </style>
</head>
<body>

<div class="container">
    <div class="workspace-header">
        <h1>Registrants Event Filter</h1>
        <a class="btn-back" href="../registrants.php">← Roster Sheet</a>
    </div>

    <div class="workspace-body">
        
        <div class="filter-section">
            <span class="filter-label">Filter by Target Event:</span>
            <form method="GET" id="filterForm">
                <select name="event_id" class="select-input" onchange="document.getElementById('filterForm').submit();">
                    <option value="all" <?php if($selected_event_id === 'all') echo 'selected'; ?>>Show All Registered Events</option>
                    <?php while($ev = mysqli_fetch_assoc($events_result)) { ?>
                        <option value="<?php echo $ev['id']; ?>" <?php if($selected_event_id == $ev['id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($ev['title']); ?>
                        </option>
                    <?php } ?>
                </select>
            </form>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Email Workspace Address</th>
                        <th>Assigned Target Event</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($students_result)){ ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['event_title']); ?></td>
                    </tr>
                    <?php } ?>
                    
                    <?php if(mysqli_num_rows($students_result) === 0): ?>
                    <tr>
                        <td colspan="3" style="text-align: center; color: #94a3b8; padding: 40px; font-style: italic;">
                            No active student rosters found matching this specific event criteria selection.
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