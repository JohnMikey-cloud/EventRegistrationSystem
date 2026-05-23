<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 🔒 Hard Security Checkpoint: Prevent unauthorized users from modifying data
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit();
}

include("../../includes/db.php");

if (!isset($_GET['id'])) {
    die("No Event ID Found");
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

$query = "SELECT * FROM events WHERE id='$id'";
$result = mysqli_query($conn, $query);
$event = mysqli_fetch_assoc($result);

if (!$event) {
    die("Event not found");
}

if (isset($_POST['update'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);

    $update = "UPDATE events 
               SET title='$title',
                   description='$description',
                   event_date='$date'
               WHERE id='$id'";

    mysqli_query($conn, $update);

    header("Location: admin_events.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event - Admin Panel</title>
    
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
            background-image: 
            radial-gradient(circle at 50% 50%, rgba(255, 102, 0, 0.08) 0%, transparent 60%),
            linear-gradient(135px, #111215 0%, #1c1e22 100__);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* Card Wrapper to Match Login layout */
        .edit-wrapper {
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 500px;
            border: 1px solid rgba(255, 102, 0, 0.05);
            transition: transform 0.3s ease;
        }

        /* Dark Card Top Header */
        .edit-header {
            background-color: #212529;
            padding: 35px 30px;
            text-align: center;
            color: #ffffff;
            border-bottom: 4px solid #ff6600;
        }

        .edit-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .edit-header p {
            color: #a0aec0;
            font-size: 0.85rem;
            margin-top: 5px;
        }

        /* Form Workspace Body */
        .edit-body {
            padding: 40px 35px;
            background: #ffffff;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #4a5568;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        /* Input Controls to Match Modern Styling */
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
            height: 110px;
            resize: vertical;
        }

        /* Primary Action Buttons & Navigation links */
        .action-container {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 30px;
        }

        .btn-update {
            background-color: #ff6600;
            color: #ffffff;
            font-weight: 600;
            padding: 13px;
            border-radius: 10px;
            border: none;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.2s ease;
            width: 100%;
            text-align: center;
        }

        .btn-update:hover {
            background-color: #cc5200;
        }

        .btn-cancel {
            display: block;
            width: 100%;
            padding: 12px;
            text-align: center;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            border-radius: 10px;
            background: transparent;
            color: #4a5568;
            border: 1.5px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .btn-cancel:hover {
            background: #f7fafc;
            color: #1a202c;
            border-color: #cbd5e1;
        }
    </style>
</head>

<body>

<div class="edit-wrapper">
    <div class="edit-header">
        <h2>Modify Event Manifest</h2>
        <p>Updating Manifest Record ID: #<?php echo htmlspecialchars($id); ?></p>
    </div>

    <div class="edit-body">
        <form method="POST">
            
            <div class="form-group">
                <label class="form-label" for="title">Event Title</label>
                <input type="text" 
                       name="title" 
                       id="title" 
                       value="<?php echo htmlspecialchars($event['title']); ?>" 
                       required>
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Detailed Description</label>
                <textarea name="description" 
                          id="description" 
                          required><?php echo htmlspecialchars($event['description']); ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="date">Target Calendar Date</label>
                <input type="date" 
                       name="date" 
                       id="date" 
                       value="<?php echo htmlspecialchars($event['event_date']); ?>" 
                       required>
            </div>

            <div class="action-container">
                <button type="submit" name="update" class="btn-update">
                    Commit Changes Securely
                </button>
                <a href="admin_events.php" class="btn-cancel">
                    Discard and Return
                </a>
            </div>

        </form>
    </div>
</div>

</body>
</html>