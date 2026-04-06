<?php
session_start();

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fname = trim($_POST['fname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $psw = $_POST['psw'] ?? '';
    $confirm = $_POST['psw-confirm'] ?? '';

    // Simple validation
    if ($psw !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // (Optional: you can insert into DB here later)
        $success = "Registration successful! You can now view submissions.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link rel="stylesheet" href="styles.css">
</head>
<body>

<nav class="navbar">
    <div class="container">
        <a class="brand" href="index.php">Conference Management System</a>

        <div class="nav-buttons">
            <a class="btn btn-outline nav-link active" href="registration.php">Registration</a>
            <a class="btn btn-outline nav-link" href="submissions.php">Submissions</a>
            <a class="btn btn-outline nav-link" href="create_submissions.php">Submit Paper</a>
        </div>
    </div>
</nav>

<div class="content-area">
    <div class="wrapper">
        <div class="form-box-register">

            <h2 class="text-center mb-4">Register</h2>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-3">
                    <label class="form-label">First name</label>
                    <input type="text" class="form-control" name="fname" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Last name</label>
                    <input type="text" class="form-control" name="lname" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email address</label>
                    <input type="email" class="form-control" name="email" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input
                        type="password"
                        class="form-control"
                        name="psw"
                        required
                        pattern="(?=.*\d).{7,12}"
                        title="Password must contain 7 - 12 characters with at least one number."
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirm password</label>
                    <input type="password" class="form-control" name="psw-confirm" required>
                </div>

                <div class="mb-3">
                    <label class="form-label d-block">Research student?</label>
                    <input type="radio" name="research" value="yes" required> Yes
                    <input type="radio" name="research" value="no"> No
                </div>

                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="terms" required>
                    <label class="form-check-label" for="terms">
                        I agree to terms and conditions
                    </label>
                </div>

                <div class="d-flex gap-2">
                    <a href="index.php" class="btn btn-danger w-50">Cancel</a>
                    <button type="submit" class="btn btn-success w-50">Submit</button>
                </div>
            </form>

            <?php if ($success): ?>
                <div class="text-center mt-4">
                    <a href="submissions.php" class="btn btn-primary">
                        Go to Submissions
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>