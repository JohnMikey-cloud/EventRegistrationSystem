<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .custom-navbar {
            background-color: #212529 !important;
            border-bottom: 3px solid #ff6600;
            padding: 15px 0;
        }
        .nav-link-custom {
            color: #ffffff !important;
            font-weight: 600;
            transition: color 0.2s ease;
            font-size: 0.95rem;
        }
        .nav-link-custom:hover {
            color: #ff6600 !important;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg custom-navbar sticky-top">
    <div class="container">
        
        <a class="navbar-brand d-flex align-items-center gap-2" href="/event_system/student/dashboard.php" style="letter-spacing: -0.3px; text-decoration: none;">
            <div class="d-flex align-items-center justify-content-center" style="background: #ff6600; width: 35px; height: 35px; border-radius: 8px;">
                <span style="color: white; font-weight: 800; font-size: 1.1rem; font-family: sans-serif;">E</span>
            </div>
            <span class="fs-5 text-white" style="font-family: sans-serif;">
                <span class="fw-bold">Event</span><span class="fw-bold"> | </span><span style="color: #ff6600; font-weight: 300;">Hub</span>
            </span>
        </a>

        <button class="navbar-toggler border-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#mainApplicationNavbar">
            <span class="navbar-toggler-icon-light" style="filter: invert(1);">▼</span>
        </button>

        <div class="collapse navbar-collapse" id="mainApplicationNavbar">
            <ul class="navbar-nav ms-auto align-items-center gap-3">
                
                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="/event_system/student/dashboard.php">Dashboard</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="/event_system/student/register_event.php">Explore Events</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="/event_system/student/my_events.php">My Schedule</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="/event_system/student/certificate.php">Certificates</a>
                </li>
                
                <li class="nav-item ms-lg-2">
                     <a href="/event_system/student/profile.php" class="text-decoration-none d-block">
                       <span class="badge bg-dark border border-secondary text-white px-3 py-2 fw-semibold rounded-pill style-badge-hover" style="transition: all 0.2s ease; cursor: pointer;">
                       👤 <?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'Student'; ?>
                      </span>
                     </a>
                </li>
                <li class="nav-item ms-lg-1">
                    <a class="btn btn-sm btn-danger fw-bold px-3 py-1.5" href="/event_system/auth/login.php" style="background-color: #dc3545; border: none; border-radius: 8px;">
                        Logout
                    </a>
                </li>

            </ul>
        </div>

    </div>
</nav>

<div class="container-fluid content-body-offset mt-4">