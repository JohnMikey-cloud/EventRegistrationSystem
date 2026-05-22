<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("../includes/db.php");

// Security Check: Enforce student-only access
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student'){
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$student_name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Student';

// --- DATA ENGINE: FETCH METRICS FOR SUMMARY CARDS ---

// 1. Count Total Available Events Map Catalog
$count_avail_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM events");
$avail_data = mysqli_fetch_assoc($count_avail_res);
$total_available_events = $avail_data['total'];

// 2. Count Active Registrations for this student
$count_reg_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM registrations WHERE user_id = '$user_id'");
$reg_data = mysqli_fetch_assoc($count_reg_res);
$total_registered_events = $reg_data['total'];

// 3. Count Verified Attendance/Certificates issued
$total_certificates = 0;
// Check if table exists to safely handle queries from previous error states
$table_check = mysqli_query($conn, "SHOW TABLES LIKE 'attendance'");
if(mysqli_num_rows($table_check) > 0) {
    $count_cert_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM attendance WHERE user_id = '$user_id'");
    $cert_data = mysqli_fetch_assoc($count_cert_res);
    $total_certificates = $cert_data['total'];
}

// Fetch the events array for the stream catalog below
$events_query = "SELECT id, title, description, event_date FROM events ORDER BY event_date ASC";
$events_result = mysqli_query($conn, $events_query);

include "../includes/header.php";
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    :root {
        --brand-orange: #ff6600;
        --brand-hover: #cc5200;
        --bg-light: #f8fafc;
        --text-dark: #0f172a;
        --text-muted: #64748b;
    }

    body {
        background-color: var(--bg-light);
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        color: var(--text-dark);
    }

    /* Welcome Hero Section Banner styling */
    .dashboard-hero {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 24px;
        padding: 40px;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        margin-bottom: 40px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
    }

    .dashboard-hero::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,102,0,0.15) 0%, rgba(255,102,0,0) 70%);
        border-radius: 50%;
    }

    .hero-title {
        font-weight: 800;
        letter-spacing: -1px;
        font-size: 2.25rem;
    }

    .hero-brand-tag {
        color: var(--brand-orange);
    }

    /* Interactive Operational Navigation Control Panel Quick links */
    .quick-link-card {
        background: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 16px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        text-decoration: none;
        color: var(--text-dark);
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .quick-link-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 20px rgba(0, 0, 0, 0.04);
        border-color: var(--brand-orange);
        color: var(--text-dark);
    }

    .icon-wrapper-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        font-weight: bold;
    }

    /* Metric Analytic Counters widgets decoration */
    .stat-badge-pill {
        font-size: 0.8rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 30px;
        background: #f1f5f9;
        color: var(--text-muted);
    }

    /* Premium Unified Stream Event Card elements */
    .event-stream-card {
        background: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 16px;
        transition: all 0.2s ease;
    }

    .event-stream-card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.03);
        border-color: #cbd5e1;
    }

    .btn-register-action {
        background-color: var(--brand-orange);
        color: #ffffff;
        font-weight: 600;
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        transition: all 0.15s ease;
    }

    .btn-register-action:hover {
        background-color: var(--brand-hover);
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(255, 102, 0, 0.2);
    }

    .section-header-title {
        font-weight: 700;
        color: var(--text-dark);
        letter-spacing: -0.5px;
    }
</style>

<div class="container py-4">

    <div class="dashboard-hero">
        <div class="row align-items-center">
            <div class="col-12 col-md-8 text-center text-md-start">
                <span class="badge bg-warning text-dark mb-2 fw-bold px-3 py-1.5 rounded-pill text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                    Student Workspace Active
                </span>
                <h1 class="hero-title m-0">Welcome back, <span class="hero-brand-tag"><?php echo htmlspecialchars($student_name); ?></span>!</h1>
                <p class="text-white-50 m-0 mt-2 small" style="max-width: 550px;">
                    Access your centralized command interface to explore upcoming computing symposiums, monitor registered schedules, and acquire institutional certificates.
                </p>
            </div>
            <div class="col-12 col-md-4 text-center text-md-end mt-4 mt-md-0 d-none d-md-block">
                <div style="font-size: 4rem; opacity: 0.85;">⚡</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-5">
        
        <div class="col-12 col-md-4">
            <a href="my_events.php" class="quick-link-card shadow-sm">
                <div class="icon-wrapper-box bg-primary-subtle text-primary">
                    <i class="bi bi-calendar-event"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold m-0">My Schedule</h6>
                        <span class="stat-badge-pill"><?php echo $total_registered_events; ?> Active</span>
                    </div>
                    <small class="text-muted small">Track your booked itineraries</small>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-4">
            <a href="register_event.php" class="quick-link-card shadow-sm">
                <div class="icon-wrapper-box bg-warning-subtle text-warning" style="color: #b45309 !important;">
                    <i class="bi bi-compass"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold m-0">Explore Catalog</h6>
                        <span class="stat-badge-pill"><?php echo $total_available_events; ?> Open</span>
                    </div>
                    <small class="text-muted small">Browse current active seats</small>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-4">
            <a href="certificate.php" class="quick-link-card shadow-sm">
                <div class="icon-wrapper-box bg-success-subtle text-success">
                    <i class="bi bi-trophy"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold m-0">Certificates Vault</h6>
                        <span class="stat-badge-pill"><?php echo $total_certificates; ?> Issued</span>
                    </div>
                    <small class="text-muted small">Download verified achievement PDF files</small>
                </div>
            </a>
        </div>

    </div>

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="section-header-title m-0">Available Events Pipeline</h3>
            <p class="text-muted small m-0 mt-1">Reserve slots for verified operational learning events instantly.</p>
        </div>
        <a href="register_event.php" class="btn btn-sm btn-outline-dark fw-semibold px-3 py-2" style="border-radius: 10px;">
            View All Catalog →
        </a>
    </div>

    <div class="row g-3">
        <?php 
        if(mysqli_num_rows($events_result) > 0) {
            while($row = mysqli_fetch_assoc($events_result)) {
                $formatted_date = date("F d, Y", strtotime($row['event_date']));
        ?>
            <div class="col-12">
                <div class="card event-stream-card shadow-sm p-4">
                    <div class="row align-items-center g-3">
                        <div class="col-12 col-md-9">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge bg-light text-secondary border fw-medium px-2 py-1 rounded">
                                    <i class="bi bi-clock me-1"></i> <?php echo $formatted_date; ?>
                                </span>
                                <small class="text-muted fw-semibold">Event ID: #<?php echo $row['id']; ?></small>
                            </div>
                            <h4 class="fw-bold text-dark mb-2" style="letter-spacing: -0.3px; font-size: 1.3rem;">
                                <?php echo htmlspecialchars($row['title']); ?>
                            </h4>
                            <p class="text-muted small m-0" style="line-height: 1.6; max-width: 800px;">
                                <?php echo htmlspecialchars($row['description']); ?>
                            </p>
                        </div>
                        <div class="col-12 col-md-3 text-md-end">
                            <a href="register_event.php?id=<?php echo $row['id']; ?>" class="btn btn-register-action w-100 w-md-auto shadow-sm">
                                <i class="bi bi-pencil-square me-1"></i> Register Event
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php 
            }
        } else {
        ?>
            <div class="col-12">
                <div class="text-center py-5 card bg-white border border-dashed rounded-3">
                    <div class="fs-1 mb-2 text-muted"><i class="bi bi-calendar-x"></i></div>
                    <h5 class="fw-bold text-dark m-0">No events currently announced</h5>
                    <p class="text-muted small m-0 mt-1">Check back later for updated academic summit pipelines.</p>
                </div>
            </div>
        <?php 
        }
        ?>
    </div>

</div>

<footer class="mt-5 py-4 border-top bg-white">
    <div class="container text-center text-md-start">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <p class="mb-0 small text-muted">&copy; <?php echo date('Y'); ?> EventHub Portal Environment. All rights reserved.</p>
            <div class="d-flex gap-3 small">
                <a href="profile.php" class="text-decoration-none text-muted">Account Profile</a>
                <span class="text-muted opacity-25">|</span>
                <a href="change_password.php" class="text-decoration-none text-muted">Security Center</a>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>