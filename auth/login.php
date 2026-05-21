<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once "../config/db.php";

// If user already logged in → redirect
if (isset($_SESSION['user_id'])) {
    header("Location: ../dashboard.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    // Find user by email
    $user = $db->users->findOne(['email' => $email]);

    // Check user & password
    if ($user && password_verify($password, $user['password'])) {

        // Save data in session
        $_SESSION['user_id']   = (string)$user['_id'];
        $_SESSION['user_name'] = $user['name'];

        header("Location: ../dashboard.php");
        exit;

    } else {
        $_SESSION['error'] = "Invalid email or password!";
        header("Location: login.php");
        exit;
    }
}
?>

<?php include_once "../templates/header.php"; ?>

<div class="container page-wrapper mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card-modern p-4">
                <h3 class="text-center mb-3">Login</h3>

                <!-- Error Message -->
                <?php if (!empty($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?= $_SESSION['error']; ?>
                        <?php unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Success Message -->
                <?php if (!empty($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= $_SESSION['success']; ?>
                        <?php unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Login Form -->
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Login
                    </button>
                </form>

                <p class="mt-3 text-center">
                    Don't have an account?
                    <a href="signup.php">Sign up here</a>
                </p>
            </div>

        </div>
    </div>
</div>

<?php include_once "../templates/footer.php"; ?>
