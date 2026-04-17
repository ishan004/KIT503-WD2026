<?php
session_start();
require_once 'db_conn.php';

$message = "";
$remembered_email = $_COOKIE['remember_email'] ?? '';

if (isset($_SESSION["timeout_message"])) {
    $message = $_SESSION["timeout_message"];
    unset($_SESSION["timeout_message"]);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    $stmt = $pdo->prepare("SELECT * FROM Users WHERE Email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        $message = "Email not registered.";
    } elseif (!password_verify($password, $user["Password"])) {
        $message = "Incorrect password.";
    } else {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_name"] = $user["Name"];
        $_SESSION["user_email"] = $user["Email"];
        $_SESSION["last_activity"] = time();

        if (isset($_POST["remember_me"])) {
            setcookie("remember_email", $email, time() + 86400, "/");
        } else {
            setcookie("remember_email", "", time() - 3600, "/");
        }

        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php require_once 'nav.php'; ?>

<main class="content-area">
    <div class="wrapper">
        <div class="details-box" style="max-width: 600px;">
            <h2>Login</h2>

            <?php if (!empty($message)): ?>
                <div class="alert-box alert-error">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?php echo htmlspecialchars($remembered_email); ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                    >
                </div>

                <div class="form-group checkbox-row">
                    <label for="remember_me">
                        <input
                            type="checkbox"
                            id="remember_me"
                            name="remember_me"
                            <?php if (!empty($remembered_email)) echo "checked"; ?>
                        >
                        Remember Me
                    </label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="success-btn">Login</button>
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