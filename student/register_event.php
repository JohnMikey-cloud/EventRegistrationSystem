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
$show_success_modal = false;
$alert_message = "";

/* REGISTRATION ENGINE */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_register'])) {
    $event_id = mysqli_real_escape_string($conn, $_POST['event_id']);

    // Duplication Check
    $check = mysqli_query($conn,"SELECT * FROM registrations WHERE user_id='$user_id' AND event_id='$event_id'");

    if(mysqli_num_rows($check) == 0){
        mysqli_query($conn,"INSERT INTO registrations(user_id,event_id) VALUES('$user_id','$event_id')");
        // Set state flag instead of standard JavaScript alert strings
        $show_success_modal = true;
    } else {
        $alert_message = "
        <div class='alert alert-warning alert-dismissible fade show border-0 shadow-sm fw-bold mb-4 rounded-3 d-flex align-items-center' role='alert'>
            <span class='me-2'>⚠️</span> You are already signed up for this workshop!
            <button type='button' class='btn-close ms-auto' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
    }
}

/* FETCH LIVE EVENTS */
$result = mysqli_query($conn, "SELECT * FROM events ORDER BY event_date ASC");

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
        font-family: 'Segoe UI', system-ui, sans-serif;
    }

    .page-title {
        color: var(--text-dark);
        font-weight: 800;
        letter-spacing: -1px;
    }

    .event-card {
        background: var(--card-bg);
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 20px;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        overflow: hidden;
    }

    .event-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
        border-color: rgba(255, 102, 0, 0.3);
    }

    .card-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(255, 102, 0, 0.08);
        color: var(--brand-orange);
        font-weight: 700;
        font-size: 0.75rem;
        padding: 6px 14px;
        border-radius: 30px;
        text-transform: uppercase;
    }

    .icon-wrapper {
        width: 48px;
        height: 48px;
        background: linear-gradient(135px, #fff0e6 0%, #ffe0cc 100%);
        color: var(--brand-orange);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
    }

    .btn-register {
        background-color: var(--brand-orange);
        color: #ffffff;
        font-weight: 600;
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        transition: all 0.2s ease;
    }

    .btn-register:hover {
        background-color: var(--brand-hover);
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(255, 102, 0, 0.2);
    }

    /* PREMIUM POPUP MODAL STYLING */
    .modal-content {
        border: none;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    .modal-success-header {
        background: linear-gradient(135px, #1e293b 0%, #0f172a 100%);
        padding: 40px 20px;
        text-align: center;
        color: white;
        position: relative;
    }
    .success-animated-icon {
        width: 70px;
        height: 70px;
        background: rgba(255, 102, 0, 0.15);
        border: 2px solid var(--brand-orange);
        color: var(--brand-orange);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        margin: 0 auto 15px auto;
        box-shadow: 0 0 20px rgba(255, 102, 0, 0.2);
        animation: scaleIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .btn-modal-primary {
        background: var(--brand-orange);
        color: white;
        font-weight: 600;
        border-radius: 12px;
        padding: 12px 30px;
        border: none;
        transition: background 0.2s;
    }
    .btn-modal-primary:hover {
        background: var(--brand-hover);
        color: white;
    }

    @keyframes scaleIn {
        0% { transform: scale(0); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
</style>

<div class="container py-5">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-5 gap-3">
        <div>
            <h1 class="page-title m-0 fw-bold">Available Programs</h1>
            <p class="text-muted m-0 mt-1">Discover skill-building bootcamps open for instant booking.</p>
        </div>
        <a class="btn btn-outline-dark px-4" href="dashboard.php" style="border-radius: 12px; font-weight:600;">← Dashboard Portal</a>
    </div>

    <?php echo $alert_message; ?>

    <div class="row g-4">
        <?php 
        if(mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) { 
        ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card event-card h-100 shadow-sm p-4 position-relative">
                    <span class="card-badge">ID: #<?php echo $row['id']; ?></span>
                    
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="icon-wrapper">📅</div>
                        <div>
                            <div class="small text-uppercase text-muted fw-bold" style="font-size:0.75rem;">Schedule Date</div>
                            <div class="text-dark fw-semibold"><?php echo date("F d, Y", strtotime($row['event_date'])); ?></div>
                        </div>
                    </div>

                    <div class="card-body p-0 d-flex flex-column justify-content-between">
                        <div>
                            <h4 class="fw-bold text-dark mb-2" style="font-size: 1.25rem;"><?php echo htmlspecialchars($row['title']); ?></h4>
                            <p class="text-muted small mb-4" style="line-height: 1.6;"><?php echo htmlspecialchars($row['description']); ?></p>
                        </div>

                        <form method="POST" action="register_event.php" class="m-0 pt-2">
                            <input type="hidden" name="event_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="btn_register" class="btn btn-register w-100 shadow-sm d-flex align-items-center justify-content-center gap-2">
                                <span>Complete Registration</span> →
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php 
            } 
        } else {
        ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted">No live event bookings cataloged.</p>
            </div>
        <?php 
        } 
        ?>
    </div>
</div>

<div class="modal fade" id="successModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content text-center">
            <div class="modal-success-header">
                <div class="success-animated-icon">✓</div>
                <h4 class="fw-bold m-0 text-white">Registration Verified</h4>
            </div>
            <div class="modal-body p-4 bg-white">
                <p class="text-muted m-0" style="font-size: 0.95rem; line-height: 1.6;">
                    Your seat has been reserved successfully! The itinerary has been synced directly into your schedule.
                </p>
                <div class="mt-4">
                    <a href="my_events.php" class="btn btn-modal-primary w-100 shadow-sm">
                        View My Schedule
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?php if ($show_success_modal): ?>
<script>
    // Automatically triggers our beautiful Bootstrap 5 modal on compilation
    document.addEventListener("DOMContentLoaded", function() {
        var myModal = new bootstrap.Modal(document.getElementById('successModal'));
        myModal.show();
    });
</script>
<?php endif; ?>

</body>
</html>