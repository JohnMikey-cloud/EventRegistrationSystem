<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("../includes/db.php");

$success_message = "";
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Default new registrations to the 'student' role
    $role = 'student'; 

    // Check if email already exists
    $check_email = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' LIMIT 1");
    
    if (mysqli_num_rows($check_email) > 0) {
        $error_message = "This email address is already registered.";
    } else {
        // Insert new student account
        $query = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
        if (mysqli_query($conn, $query)) {
            $success_message = "Account created successfully! Redirecting to login...";
            echo "<script>
                    setTimeout(function(){ window.location.href = 'login.php'; }, 2000);
                  </script>";
        } else {
            $error_message = "Registration failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Event Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --brand-orange: #ff6600;
            --brand-dark: #212529;
            --brand-hover: #cc5200;
        }

        body {
             background-color: #0b0c10;
             background-image: 
             radial-gradient(circle at var(--mouse-x, 50%) var(--mouse-y, 50%), rgba(255, 102, 0, 0.12) 0%, transparent 50%),
             linear-gradient(135px, #111215 0%, #1c1e22 100%);
             min-height: 100vh;
             display: flex;
             align-items: center;
             justify-content: center;
             font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
             padding: 20px;
             overflow-x: hidden;
             transition: background-color 0.3s ease;
        }

        .register-wrapper {
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 480px;
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1), box-shadow 0.3s ease;            
        }

        .register-wrapper:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px rgba(255, 102, 0, 0.08);
        }

        .register-header {
            background-color: var(--brand-dark);
            padding: 35px 30px;
            text-align: center;
            color: #ffffff;
            border-bottom: 4px solid var(--brand-orange);
        }

        .brand-icon {
            font-size: 2rem;
            margin-bottom: 8px;
        }

        .register-body {
            padding: 35px 40px;
            background: #ffffff;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            padding: 11px 16px;
            border-radius: 10px;
            border: 1.5px solid #ced4da;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            background-color: #fff;
            color: #212529;
        }

        .form-control:focus {
            border-color: var(--brand-orange);
            box-shadow: 0 0 0 0.25rem rgba(255, 102, 0, 0.15);
        }

        .btn-register {
            background-color: var(--brand-orange);
            color: #ffffff;
            font-weight: 600;
            padding: 13px;
            border-radius: 10px;
            border: none;
            font-size: 1rem;
            transition: background-color 0.2s ease;
            width: 100%;
            margin-top: 15px;
        }

        .btn-register:hover {
            background-color: var(--brand-hover);
            color: #ffffff;
        }

        .register-footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 0.9rem;
        }

        .register-footer a {
            color: var(--brand-orange);
            text-decoration: none;
            font-weight: 600;
        }

        .register-footer a:hover {
            text-decoration: underline;
            color: var(--brand-hover);
        }
    </style>
</head>
<body>

<div class="register-wrapper">
    <div class="register-header">
        <h3 class="fw-bold m-0">Student Registry</h3>
        <p class="text-white-50 small m-0 mt-1">Create an account to track and attend system workshops</p>
    </div>

    <div class="register-body">
        
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success border-0 shadow-sm rounded-3 small fw-semibold py-2.5 px-3 mb-4" role="alert">
                 <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger border-0 shadow-sm rounded-3 small fw-semibold py-2.5 px-3 mb-4" role="alert">
                 <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            
            <div class="mb-3">
                <label for="name" class="form-label mb-1.5">Full Name</label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       class="form-control" 
                       placeholder="John Doe" 
                       required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label mb-1.5">Email Address</label>
                <input type="email" 
                       name="email" 
                       id="email" 
                       class="form-control" 
                       placeholder="student@example.com" 
                       required>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label mb-1.5">Password</label>
                <input type="password" 
                       name="password" 
                       id="password" 
                       class="form-control" 
                       placeholder="••••••••••••" 
                       required>
            </div>

            <button type="submit" name="btn_register" class="btn btn-register shadow-sm">
                Create Account Securely
            </button>
        </form>

        <div class="register-footer text-muted">
            Already have an active account? <a href="login.php">Sign In</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>