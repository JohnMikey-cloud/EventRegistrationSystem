<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("../includes/db.php");

// Security Check: Enforce student-only workspace access
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student'){
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch all events that this specific student registered for
$query = "
    SELECT events.id, events.title, events.description, events.event_date 
    FROM registrations
    JOIN events ON registrations.event_id = events.id
    WHERE registrations.user_id = '$user_id'
    ORDER BY events.event_date ASC
";
$result = mysqli_query($conn, $query);

include "../includes/header.php";
?>

<style>
    :root {
        --brand-orange: #ff6600;
        --brand-hover: #cc5200;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --card-bg: #ffffff;
    }

    body {
        background-color: #f8fafc;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    }

    .page-title {
        color: var(--text-dark);
        font-weight: 800;
        letter-spacing: -1px;
        font-size: 2.25rem;
    }

    /* Modern Tableless Card Rows */
    .schedule-card {
        background: var(--card-bg);
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 16px;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
    }

    .schedule-card:hover {
        transform: translateX(4px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.04) !important;
        border-left: 5px solid var(--brand-orange) !important;
    }

    /* Left Border Accent for Visual Hierarchy */
    .border-accent-orange {
        border-left: 5px solid #cbd5e1;
    }

    /* Date Badge Geometry */
    .date-badge-box {
        background: #f1f5f9;
        color: var(--text-dark);
        border-radius: 12px;
        padding: 12px;
        min-width: 90px;
        text-align: center;
    }

    .date-day {
        font-size: 1.5rem;
        font-weight: 800;
        line-height: 1;
        color: var(--brand-orange);
    }

    .date-month {
        font-size: 0.72rem;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        margin-top: 2px;
    }

    .btn-dashboard-back {
        border: 1.5px solid #e2e8f0;
        background: white;
        color: var(--text-dark);
        font-weight: 600;
        border-radius: 12px;
        padding: 10px 20px;
        transition: all 0.2s ease;
    }

    .btn-dashboard-back:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
        color: var(--text-dark);
    }

    .status-indicator {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #0f5132;
        background-color: #d1e7dd;
        padding: 4px 12px;
        border-radius: 30px;
        display: inline-block;
    }
</style>

<div class="container py-5">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-5 gap-3">
        <div>
            <h1 class="page-title m-0">My Registered Events</h1>
            <p class="text-muted m-0 mt-1">Review and keep track of your upcoming academic schedules and workshop itineraries.</p>
        </div>
        <a class="btn btn-dashboard-back shadow-sm" href="dashboard.php">
            ← Back to Dashboard
        </a>
    </div>

    <div class="row">
        <div class="col-12 col-xl-10 mx-auto">
            
            <?php 
            if(mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) { 
                    // Dynamic date parsing to create beautiful calendar blocks
                    $timestamp = strtotime($row['event_date']);
                    $dayStr   = date("d", $timestamp);
                    $monthStr = date("M", $timestamp);
                    $yearStr  = date("Y", $timestamp);
            ?>
                <div class="card schedule-card border-accent-orange p-4 mb-3 shadow-sm">
                    <div class="row align-items-center g-3">
                        
                        <div class="col-auto">
                            <div class="date-badge-box shadow-sm">
                                <div class="date-day"><?php echo $dayStr; ?></div>
                                <div class="date-month"><?php echo $monthStr; ?></div>
                                <div class="text-muted small" style="font-size: 0.65rem; font-weight:600;"><?php echo $yearStr; ?></div>
                            </div>
                        </div>

                        <div class="col col-md-8 px-md-4">
                            <div class="d-flex align-items-center gap-2 mb-1.5 flex-wrap">
                                <span class="status-indicator">Confirmed Seat</span>
                                <small class="text-muted fw-semibold">ID: #<?php echo $row['id']; ?></small>
                            </div>
                            <h4 class="fw-bold text-dark mb-1" style="font-size: 1.25rem; letter-spacing: -0.3px;">
                                <?php echo htmlspecialchars($row['title']); ?>
                            </h4>
                            <p class="text-muted small m-0" style="line-height: 1.5; max-width: 700px;">
                                <?php echo htmlspecialchars($row['description']); ?>
                            </p>
                        </div>

                        <div class="col-12 col-md-auto text-md-end ms-md-auto">
                            <span class="badge bg-light text-dark border px-3 py-2 fw-semibold rounded-pill">
                                📅 Verified Slot
                            </span>
                        </div>

                    </div>
                </div>
            <?php 
                } 
            } else {
            ?>
                <div class="text-center py-5 card bg-white border rounded-3 shadow-sm">
                    <div class="fs-1 mb-2">🗓️</div>
                    <h5 class="fw-bold text-dark m-0">No active reservations found</h5>
                    <p class="text-muted small m-0 mt-1">You haven't signed up for any events yet.</p>
                    <div class="mt-3">
                        <a href="register_event.php" class="btn btn-sm btn-warning fw-bold px-3 py-2 text-white" style="background:#ff6600; border:none; border-radius:8px;">
                            Browse Events Map
                        </a>
                    </div>
                </div>
            <?php 
            } 
            ?>

        </div>
    </div>

</div>

<footer class="mt-5 py-4 border-top bg-white">
    <div class="container text-center text-md-start">
        <p class="mb-0 small text-muted">&copy; <?php echo date('Y'); ?> EventHub Workspace Ecosystem. All operational rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>