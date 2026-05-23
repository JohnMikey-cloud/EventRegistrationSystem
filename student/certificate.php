<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("../includes/db.php");

// Enforce security authorization gate
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student'){
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Safe SQL checking engine for attendance table tracking
$has_records = false;
$attendance_records = [];

$table_check = mysqli_query($conn, "SHOW TABLES LIKE 'attendance'");
if(mysqli_num_rows($table_check) > 0) {
    // Fetch details if student has verified event presence markers
    $query = "SELECT a.attended_at, e.title, e.event_date 
              FROM attendance a 
              JOIN events e ON a.event_id = e.id 
              WHERE a.user_id = '$user_id' 
              ORDER BY a.attended_at DESC";
    $result = mysqli_query($conn, $query);
    if($result && mysqli_num_rows($result) > 0) {
        $has_records = true;
        while($row = mysqli_fetch_assoc($result)) {
            $attendance_records[] = $row;
        }
    }
}

include "../includes/header.php";
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    :root {
        --brand-orange: #ff6600;
        --brand-hover: #cc5200;
        --text-dark: #0f172a;
        --text-muted: #64748b;
    }
    body { 
        background-color: #f8fafc; 
        font-family: 'Segoe UI', system-ui, sans-serif;
        color: var(--text-dark);
    }
    .vault-card {
        background: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 24px;
        max-width: 800px;
        margin: 0 auto;
    }
    .empty-illustration-box {
        padding: 60px 20px;
    }
    .badge-award-icon {
        width: 100px;
        height: 100px;
        background: rgba(255, 102, 0, 0.06);
        color: var(--brand-orange);
        font-size: 3.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px auto;
        border: 2px dashed rgba(255, 102, 0, 0.2);
    }
    .btn-action-back {
        background-color: #1e293b;
        color: #ffffff;
        font-weight: 600;
        border: none;
        border-radius: 12px;
        padding: 12px 30px;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
    }
    .btn-action-back:hover {
        background-color: #0f172a;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
    }
    .btn-explore {
        background-color: var(--brand-orange);
        color: white;
        font-weight: 600;
        border-radius: 12px;
        padding: 12px 30px;
        text-decoration: none;
        display: inline-block;
        transition: all 0.2s ease;
    }
    .btn-explore:hover {
        background-color: var(--brand-hover);
        color: white;
        box-shadow: 0 4px 12px rgba(255, 102, 0, 0.2);
    }
    .certificate-list-item {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        transition: all 0.2s ease;
        background: #ffffff;
    }
    .certificate-list-item:hover {
        border-color: var(--brand-orange);
        box-shadow: 0 6px 18px rgba(0,0,0,0.02);
    }
</style>

<div class="container py-5">
    <div class="vault-card shadow-sm p-4 p-md-5">
        
        <div class="d-flex justify-content-between align-items-center border-bottom pb-4 mb-4 flex-wrap gap-3">
            <div>
                <h3 class="fw-bold m-0" style="letter-spacing: -0.5px;">Certification Ledger</h3>
                <p class="text-muted small m-0 mt-1">Review and print your official event completion documents.</p>
            </div>
            <a href="dashboard.php" class="btn btn-sm btn-outline-secondary px-3 py-2 fw-semibold" style="border-radius: 10px;">
                <i class="bi bi-arrow-left me-1"></i> Dashboard
            </a>
        </div>

        <?php if (!$has_records): ?>
            <div class="empty-illustration-box text-center">
                <div class="badge-award-icon">
                    <i class="bi bi-patch-check"></i>
                </div>
                <h4 class="fw-bold text-dark mb-2">No Certificates Issued Yet</h4>
                <p class="text-muted small mx-auto mb-4" style="max-width: 460px; line-height: 1.6;">
                    Your credential vault is currently empty. Once your attendance is verified by event coordinators post-session, your official downloadable certificates will unlock here automatically.
                </p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="dashboard.php" class="btn btn-action-back">
                        <i class="bi bi-house me-1"></i> Return Home
                    </a>
                    <a href="register_event.php" class="btn btn-explore">
                        <i class="bi bi-compass me-1"></i> Explore Available Events
                    </a>
                </div>
            </div>
        
        <?php else: ?>
            <div class="row g-3">
                <?php foreach ($attendance_records as $cert): ?>
                    <div class="col-12">
                        <div class="certificate-list-item p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <span class="badge bg-success-subtle text-success mb-2 fw-semibold px-2 py-1 rounded small">
                                    <i class="bi bi-check2-all me-1"></i> Verified Attended
                                </span>
                                <h5 class="fw-bold text-dark mb-1" style="font-size: 1.15rem;"><?php echo htmlspecialchars($cert['title']); ?></h5>
                                <small class="text-muted d-block">
                                    <i class="bi bi-calendar3 me-1"></i> Conducted: <?php echo date("M d, Y", strtotime($cert['event_date'])); ?>
                                </small>
                            </div>
                            <div>
                                <button onclick="window.print()" class="btn btn-sm btn-dark px-3 py-2 fw-semibold" style="border-radius: 10px; background-color:#ff6600; border:none;">
                                    <i class="bi bi-printer me-1"></i> Print Certificate
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>