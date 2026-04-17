<?php
session_start();
require_once 'db_conn.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fname = trim($_POST['fname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $psw = $_POST['psw'] ?? '';
    $confirm = $_POST['psw-confirm'] ?? '';
    $research = $_POST['research'] ?? '';

    if ($fname === '' || $lname === '' || $email === '' || $psw === '' || $confirm === '' || $research === '') {
        $error = "Please fill in all fields.";
    } elseif ($psw !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $check = $pdo->prepare("SELECT id FROM Users WHERE Email = ?");
        $check->execute([$email]);
        $existing = $check->fetch();

        if ($existing) {
            $error = "Email already registered.";
        } else {
            $fullName = $fname . " " . $lname;
            $hashedPassword = password_hash($psw, PASSWORD_DEFAULT);
            $affiliation = ($research === "yes") ? "Research Student" : "Non-research Student";
            $role = "Author";

            $stmt = $pdo->prepare("
                INSERT INTO Users (Name, Email, Password, affiliation, Role)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$fullName, $email, $hashedPassword, $affiliation, $role]);

            $_SESSION["user_id"] = $pdo->lastInsertId();
            $_SESSION["user_name"] = $fullName;
            $_SESSION["user_email"] = $email;
            $_SESSION["last_activity"] = time();

            header("Location: index.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php require_once 'nav.php'; ?>

<main class="content-area">
    <div class="wrapper">
        <div class="details-box" style="max-width: 700px;">
            <h2>Conference Registration</h2>

            <?php if (!empty($error)): ?>
                <div class="alert-box alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="post" id="registrationForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="fname">First Name</label>
                        <input type="text" id="fname" name="fname" required>
                    </div>

                    <div class="form-group">
                        <label for="lname">Last Name</label>
                        <input type="text" id="lname" name="lname" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="psw">Password</label>
                        <input type="password" id="psw" name="psw" required>
                    </div>

                    <div class="form-group">
                        <label for="pswConfirm">Confirm Password</label>
                        <input type="password" id="pswConfirm" name="psw-confirm" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Research Student?</label>
                    <div class="inline-options">
                        <label for="research_yes">
                            <input type="radio" id="research_yes" name="research" value="yes" required>
                            Yes
                        </label>

                        <label for="research_no">
                            <input type="radio" id="research_no" name="research" value="no">
                            No
                        </label>
                    </div>
                </div>

                <div class="form-group checkbox-row">
                    <label for="terms">
                        <input type="checkbox" id="terms" required>
                        I agree to the terms and conditions
                    </label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="success-btn">Submit Registration</button>
                    <a href="index.php" class="secondary-btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</main>

<footer>
    <div class="container footer-row">
        <p>&copy; 2026 Conference Management System</p>
    </div>
</footer>

<script src="script.js"></script>
</body>
</html>