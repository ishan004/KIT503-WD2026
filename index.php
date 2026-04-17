<?php
require_once 'session_timeout.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conference Management System - Home</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link rel="stylesheet" href="styles.css">

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<?php require_once 'nav.php'; ?>

<div id="myCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
    <div class="carousel-inner">

        <div class="carousel-item active" data-bs-interval="2500">
            <img src="images/forest.jpg" class="d-block w-100" alt="Conference banner">
             <div class="top-left-label">
        <?php if (isset($_SESSION["user_id"])): ?>
            Hello! <?php echo htmlspecialchars($_SESSION["user_name"]); ?>
        <?php else: ?>
            Hello! Guest
        <?php endif; ?> 
    </div>
            <div class="carousel-caption">
                <h5>Welcome to the Conference Management System</h5>
                <p>Manage registrations, submissions, paper details, and reviews in one place.</p>
            </div>
        </div>

        <div class="carousel-item" data-bs-interval="2500">
            <img src="images/utas.jpg" class="d-block w-100" alt="UTAS campus">
            <div class="top-left-label">
        <?php if (isset($_SESSION["user_id"])): ?>
            Hello! <?php echo htmlspecialchars($_SESSION["user_name"]); ?>
        <?php else: ?>
            Hello! Guest
        <?php endif; ?> 
    </div>
            <div class="carousel-caption">
                <h5>UTAS Conference Submission Portal</h5>
                <p>Browse paper submissions, submit new work, and update existing records.</p>
            </div>
        </div>

    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
    </button>
</div>

<main class="content-area">
    <div class="wrapper">
        <div class="hero-box">
            <h1>Conference Management System</h1>
            <p>
                This system supports registration, login, conference details, and paper submissions.
            </p>

            <div class="hero-actions">
                <?php if (isset($_SESSION["user_id"])): ?>
                    <a href="submissions.php" class="success-btn">View Submissions</a>
                    <a href="conference_details.php" class="secondary-btn">Conference Details</a>
                <?php else: ?>
                    <a href="registration.php" class="secondary-btn">Register</a>
                    <a href="login.php" class="success-btn">Login</a>
                    <a href="conference_details.php" class="primary-btn">Conference Details</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<footer class="text-center">
    <p>&copy; 2026 Conference Management System</p>
</footer>

<script src="script.js"></script>
</body>
</html>