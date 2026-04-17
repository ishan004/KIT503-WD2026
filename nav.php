<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar">
    <div class="container">
        <a class="brand" href="index.php">Conference Management System</a>

        <div class="nav-buttons">
            <?php if (isset($_SESSION["user_id"])): ?>
                <span >
                    Welcome, <?php echo htmlspecialchars($_SESSION["user_name"]); ?>
                </span>
                <a class="btn btn-outline nav-link" href="submissions.php">Submissions</a>
                <a class="btn btn-outline nav-link" href="conference_details.php">Conference Details</a>
                <a class="btn btn-outline nav-link" href="logout.php">Logout</a>
            <?php else: ?>
                <a class="btn btn-outline nav-link" href="registration.php">Registration</a>
                <a class="btn btn-outline nav-link" href="login.php">Login</a>
                <a class="btn btn-outline nav-link" href="conference_details.php">Conference Details</a>
            <?php endif; ?>
        </div>
    </div>
</nav>