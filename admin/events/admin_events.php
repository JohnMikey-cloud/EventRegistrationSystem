<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 🔒 Hard Security Checkpoint: Prevent unauthorized users from viewing this file directly
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit();
}

include("../../includes/db.php");

if (isset($_POST['add_event'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $event_date = mysqli_real_escape_string($conn, $_POST['event_date']);

    $insert = "INSERT INTO events(title, description, event_date)
               VALUES('$title','$description','$event_date')";

    mysqli_query($conn, $insert);
}

$events = mysqli_query($conn, "SELECT * FROM events ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events - Admin Panel</title>
    
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

        /* Large Workspace Workspace Container */
        .container {
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 102, 0, 0.05);
        }

        /* Account Gateway Matching Header */
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

        /* Form & Content Layout */
        .workspace-body {
            padding: 40px;
        }

        .form-section {
            background: #f8fafc;
            padding: 30px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            margin-bottom: 40px;
        }

        .form-grid {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .form-label {
            font-weight: 600;
            color: #4a5568;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: -6px;
        }

        /* Upgraded Inputs to Match Login Form */
        input[type="text"], input[type="date"], textarea {
            width: 100%;
            padding: 12px 16px;
            border-radius: 10px;
            border: 1.5px solid #ced4da;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            background-color: #ffffff;
            color: #212529;
            font-family: inherit;
        }

        input[type="text"]:focus, input[type="date"]:focus, textarea:focus {
            border-color: #ff6600;
            box-shadow: 0 0 0 0.25rem rgba(255, 102, 0, 0.15);
            outline: none;
        }

        textarea {
            height: 100px;
            resize: vertical;
        }

        .btn-add {
            background-color: #ff6600;
            color: #ffffff;
            font-weight: 600;
            padding: 13px 25px;
            border-radius: 10px;
            border: none;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.2s ease;
            align-self: flex-start;
        }

        .btn-add:hover {
            background-color: #cc5200;
        }

        /* Clean Modern Data Table CSS */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
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

        /* Specific Width Constraints for Columns */
        th:nth-child(1), td:nth-child(1) { width: 8%; text-align: center; }
        th:nth-child(4), td:nth-child(4) { width: 15%; }
        th:nth-child(5), td:nth-child(5) { width: 20%; text-align: center; }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        /* Action Buttons styling */
        .edit-btn, .delete-btn {
            display: inline-block;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.2s ease;
            text-align: center;
        }

        .edit-btn {
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
        }

        .edit-btn:hover {
            background: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
        }

        .delete-btn {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .delete-btn:hover {
            background: #dc2626;
            color: #ffffff;
            border-color: #dc2626;
        }
    </style>
</head>

<body>

<div class="container">
    <div class="workspace-header">
        <h1>Manage Event Manifests</h1>
        <a class="btn-back" href="../dashboard.php">← Return to Dashboard</a>
    </div>

    <div class="workspace-body">
        <div class="form-section">
            <form method="POST" class="form-grid">
                <div>
                    <label class="form-label" for="title">Event Title</label>
                    <input type="text" name="title" id="title" placeholder="e.g. Annual Tech Symposium" required>
                </div>

                <div>
                    <label class="form-label" for="description">Event Summary</label>
                    <textarea name="description" id="description" placeholder="Provide a breakdown of schedule details, rules, and venue rules..." required></textarea>
                </div>

                <div>
                    <label class="form-label" for="event_date">Scheduled Date</label>
                    <input type="date" name="event_date" id="event_date" required>
                </div>

                <button type="submit" name="add_event" class="btn-add">Publish Event Listing</button>
            </form>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="text-align: center;">ID</th>
                        <th>Event Title</th>
                        <th>Description</th>
                        <th>Target Date</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($events)){ ?>
                    <tr>
                        <td style="text-align: center; font-weight: 600; color: #64748b;"><?php echo $row['id']; ?></td>
                        <td style="font-weight: 600; color: #1e293b;"><?php echo htmlspecialchars($row['title']); ?></td>
                        <td style="color: #475569; line-height: 1.5;"><?php echo htmlspecialchars($row['description']); ?></td>
                        <td style="font-weight: 500; color: #475569;"><?php echo htmlspecialchars($row['event_date']); ?></td>
                        <td>
                            <div class="action-buttons">
                                <a class="edit-btn" href="admin_edit_event.php?id=<?php echo $row['id']; ?>">Modify</a>
                                <a class="delete-btn" href="admin_delete_event.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to completely remove this event entry?')">Remove</a>
                            </div>
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