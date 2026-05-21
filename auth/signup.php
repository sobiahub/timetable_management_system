<?php
// Session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once "../config/db.php";

// Agar user already login hai to dashboard bhej do
if (isset($_SESSION['user_id'])) {
    header("Location: ../dashboard.php");
    exit;
}

// Form submit hone par
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    // Password match check
    if ($password !== $confirm) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: signup.php");
        exit;
    }

    // Email already exist check
    $user = $db->users->findOne(['email' => $email]);
    if ($user) {
        $_SESSION['error'] = "Email already registered!";
        header("Location: signup.php");
        exit;
    }

    // Password secure banane ke liye hash
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // New user insert
    $db->users->insertOne([
        'name'       => $name,
        'email'      => $email,
        'password'   => $hashedPassword,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]);

    // Success message
    $_SESSION['success'] = "Account created successfully! Please login.";
    header("Location: login.php");
    exit;
}
?>

<?php include_once "../templates/header.php"; ?>

<div class="container page-wrapper mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card-modern p-4">
                <h3 class="text-center mb-3">Sign Up</h3>

                <!-- Error message -->
                <?php if (!empty($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?= $_SESSION['error']; ?>
                        <?php unset($_SESSION['error']); ?>
                        <button class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Signup Form -->
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Sign Up
                    </button>
                </form>

                <p class="mt-3 text-center">
                    Already have an account?
                    <a href="login.php">Login here</a>
                </p>
            </div>

        </div>
    </div>
</div>

<?php include_once "../templates/footer.php"; ?>
