<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="/assets/css/style.css">

    <title>Timetable Management System</title>
</head>
<body>

<header class="site-header">
    <nav class="navbar navbar-expand-lg navbar-dark custom-navbar">
        <div class="container-fluid header-container">

            <a class="navbar-brand d-flex align-items-center" href="/index.php">
                <img src="/assets/images/app-logo.jfif" class="logo-img" alt="Logo">
                <span class="brand-text">Timetable System</span>
            </a>

            <button class="navbar-toggler" type="button"
                data-toggle="collapse"
                data-target="#mainNav"
                aria-controls="mainNav"
                aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ml-auto">

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/views/timetable.php">
                                <i class="fas fa-calendar-alt"></i> Timetable
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/views/datesheet.php">
                                <i class="fas fa-file-alt"></i> Date Sheet
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/auth/logout.php">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/auth/login.php">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/auth/signup.php">
                                <i class="fas fa-user-plus"></i> Signup
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>
            </div>

        </div>
    </nav>
</header>
