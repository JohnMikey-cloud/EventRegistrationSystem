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
$status_message = "";
$show_routing_modal = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_confirm_change'])) {
    $current_password = mysqli_real_escape_string($conn, $_POST['current_password']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    
    $user_check = mysqli_query($conn, "SELECT password FROM users WHERE id='$user_id' LIMIT 1");
    $user_data = mysqli_fetch_assoc($user_check);
    
    if ($user_data['password'] === $current_password) {
        if(!empty($new_password)) {
            mysqli_query($conn, "UPDATE users SET password='$new_password' WHERE id='$user_id'");
            // Trigger the follow-up decision modal flags instead of basic text banners
            $show_routing_modal = true;
        }
    } else {
        $status_message = "
        <div class='alert alert-danger border-0 shadow-sm rounded-3 small fw-bold p-3 mb-4 d-flex align-items-center' role='alert'>
            <span class='me-2'>❌</span> Verification Failed: Current password statement is invalid.
        </div>";
    }
}

include "../includes/header.php";
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    :root {
        --brand-orange: #ff6600;
        --brand-hover: #cc5200;
        --text-dark: #1e293b;
    }
    body { background-color: #f8fafc; font-family: 'Segoe UI', system-ui, sans-serif; }
    .security-card { background: #ffffff; border: 1px solid rgba(226, 232, 240, 0.8); border-radius: 20px; max-width: 600px; margin: 0 auto; }
    .form-label-custom { font-weight: 700; color: var(--text-dark); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; }
    
    .password-input-container { position: relative; }
    .form-control-custom { padding: 12px 50px 12px 16px; border-radius: 12px; border: 1.5px solid #e2e8f0; font-size: 0.95rem; background-color: #f8fafc; }
    .form-control-custom:focus { border-color: var(--brand-orange); background-color: #fff; box-shadow: 0 0 0 0.25rem rgba(255, 102, 0, 0.1); }
    
    .toggle-password-btn {
        position: absolute; right: 16px; top: 50%; transform: translateY(-50%);
        background: none; border: none; color: #94a3b8; font-size: 1.2rem; cursor: pointer; padding: 0; z-index: 10;
        display: flex; align-items: center; justify-content: center; transition: color 0.15s ease;
    }
    .toggle-password-btn:hover { color: var(--brand-orange); }

    .btn-trigger-modal {
        background: var(--brand-orange); color: white; font-weight: 600; border-radius: 12px; padding: 12px 30px; border: none; transition: all 0.2s;
    }
    .btn-trigger-modal:hover { background: var(--brand-hover); color: white; box-shadow: 0 4px 12px rgba(255, 102, 0, 0.2); }
</style>

<div class="container py-5">
    <div class="security-card shadow-sm p-4 p-md-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark m-0">Update Password</h3>
                <p class="text-muted small m-0 mt-1">Modify your security access tokens securely.</p>
            </div>
            <a href="profile.php" class="btn btn-sm btn-outline-secondary px-3 py-2 fw-semibold" style="border-radius:10px;">← Back</a>
        </div>

        <?php echo $status_message; ?>

        <form id="passwordSecurityForm" method="POST" action="change_password.php">
            <div class="mb-4">
                <label class="form-label-custom mb-1.5">Current Password</label>
                <div class="password-input-container">
                    <input type="password" id="currentPassword" name="current_password" class="form-control form-control-custom w-100" placeholder="••••••••••••" required>
                    <button type="button" class="toggle-password-btn" onclick="toggleFieldVisibility('currentPassword', this)">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label-custom mb-1.5">New Target Password</label>
                <div class="password-input-container">
                    <input type="password" id="newPassword" name="new_password" class="form-control form-control-custom w-100" placeholder="Create complex structural pass-code" required>
                    <button type="button" class="toggle-password-btn" onclick="toggleFieldVisibility('newPassword', this)">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <div class="text-end pt-2">
                <button type="button" class="btn btn-trigger-modal shadow-sm" onclick="triggerSecurityConfirmation()">
                    Update Account Key
                </button>
            </div>

            <input type="hidden" name="btn_confirm_change" value="1">
        </form>
    </div>
</div>

<div class="modal fade" id="confirmationGateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <div class="modal-content border-0" style="border-radius:20px; overflow:hidden;">
            <div class="modal-body p-4 text-center bg-white">
                <div class="text-warning mb-2" style="font-size: 3rem;"><i class="bi bi-exclamation-triangle"></i></div>
                <h4 class="fw-bold text-dark mb-2">Confirm Password Change?</h4>
                <p class="text-muted small mb-4" style="line-height:1.6;">
                    Are you absolutely sure you want to update your security keys? Doing this will override your existing credentials across active session platforms.
                </p>
                <div class="d-flex gap-3">
                    <button type="button" class="btn btn-light w-50 fw-semibold py-2.5" data-bs-dismiss="modal" style="border-radius:12px;">Cancel</button>
                    <button type="button" class="btn btn-dark w-50 fw-semibold py-2.5" onclick="executeVerifiedFormFormSubmit()" style="background:#ff6600; border:none; border-radius:12px;">Yes, Confirm Update</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="routingDecisionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
        <div class="modal-content border-0" style="border-radius:24px; overflow:hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
            <div class="modal-body p-5 text-center bg-white">
                <div class="text-success mb-3" style="font-size: 3.5rem;"><i class="bi bi-check-circle-fill"></i></div>
                <h4 class="fw-bold text-dark mb-2">Credentials Updated!</h4>
                <p class="text-muted small mb-4" style="line-height:1.6;">
                    Your account password has been successfully altered. To maintain system security protocols, would you like to terminate your active workspace session or proceed directly back to the dashboard hub?
                </p>
                <div class="d-flex flex-column gap-2">
                    <a href="../auth/login.php" class="btn btn-danger w-100 fw-bold py-2.5" style="border-radius:12px; background-color: #dc3545; border:none;">
                        <i class="bi bi-box-arrow-right me-2"></i> Secure Logout From Account
                    </a>
                    <a href="dashboard.php" class="btn btn-light w-100 fw-semibold py-2.5 text-dark border" style="border-radius:12px;">
                        <i class="bi bi-speedometer2 me-2"></i> Keep Me Signed In, Go to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleFieldVisibility(fieldId, buttonElement) {
        const targetInput = document.getElementById(fieldId);
        const iconElement = buttonElement.querySelector('i');
        
        if (targetInput.type === "password") {
            targetInput.type = "text";
            iconElement.className = "bi bi-eye-slash";
        } else {
            targetInput.type = "password";
            iconElement.className = "bi bi-eye";
        }
    }

    function triggerSecurityConfirmation() {
        const form = document.getElementById('passwordSecurityForm');
        if(form.checkValidity()) {
            var confirmModal = new bootstrap.Modal(document.getElementById('confirmationGateModal'));
            confirmModal.show();
        } else {
            form.reportValidity();
        }
    }

    function executeVerifiedFormFormSubmit() {
        document.getElementById('passwordSecurityForm').submit();
    }
</script>

<?php if ($show_routing_modal): ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var routingModal = new bootstrap.Modal(document.getElementById('routingDecisionModal'));
        routingModal.show();
    });
</script>
<?php endif; ?>

</body>
</html>