<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("../includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student'){
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch current user details
$user_res = mysqli_query($conn, "SELECT name, email, role FROM users WHERE id='$user_id' LIMIT 1");
$user = mysqli_fetch_assoc($user_res);

include "../includes/header.php";
?>

<style>
    :root {
        --brand-orange: #ff6600;
        --brand-hover: #cc5200;
        --text-dark: #1e293b;
        --text-muted: #64748b;
    }
    body { background-color: #f8fafc; font-family: 'Segoe UI', system-ui, sans-serif; }
    .profile-card { background: #ffffff; border: 1px solid rgba(226, 232, 240, 0.8); border-radius: 20px; }
    .avatar-display {
        width: 110px; height: 110px;
        background: linear-gradient(135px, #212529 0%, #343a40 100%);
        color: white; font-size: 3rem; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        border: 4px solid #ffffff; box-shadow: 0 8px 20px rgba(0,0,0,0.08); margin: 0 auto;
    }
    .form-label-custom { font-weight: 700; color: var(--text-dark); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .form-control-custom { padding: 12px 16px; border-radius: 12px; border: 1.5px solid #e2e8f0; font-size: 0.95rem; background-color: #f8fafc; }
    .btn-action-secure {
        background: #212529; color: white; font-weight: 600; border-radius: 12px; padding: 12px 24px; border: none; transition: all 0.2s; text-decoration: none; display: inline-block;
    }
    .btn-action-secure:hover { background: #000000; color: white; box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
</style>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-12 col-lg-4">
            <div class="profile-card shadow-sm p-4 text-center">
                <div class="avatar-display mb-3">👨‍🎓</div>
                <h4 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($user['name']); ?></h4>
                <p class="text-muted small mb-3"><?php echo htmlspecialchars($user['email']); ?></p>
                <span class="badge bg-warning text-dark px-3 py-2 fw-bold rounded-pill text-uppercase" style="font-size:0.7rem;">Verified Student</span>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <div class="profile-card shadow-sm p-4 p-md-5">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
                    <div>
                        <h3 class="fw-bold text-dark mb-1">Account Identity Portal</h3>
                        <p class="text-muted small m-0">Your primary verified institutional profiles parameters.</p>
                    </div>
                    <a href="change_password.php" class="btn btn-action-secure shadow-sm">
                         Change Account Password
                    </a>
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label-custom mb-1.5">Registered Identity</label>
                        <input type="text" class="form-control form-control-custom w-100 text-muted" value="<?php echo htmlspecialchars($user['name']); ?>" readonly disabled>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label-custom mb-1.5">Primary System Email</label>
                        <input type="text" class="form-control form-control-custom w-100 text-muted" value="<?php echo htmlspecialchars($user['email']); ?>" readonly disabled>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>