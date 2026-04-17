<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$timeout_duration = 60; // 1 minute

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (isset($_SESSION["last_activity"])) {
    $inactive_time = time() - $_SESSION["last_activity"];

    if ($inactive_time > $timeout_duration) {
        session_unset();
        session_destroy();

        session_start();
        $_SESSION["timeout_message"] = "Session expired due to inactivity.";

        header("Location: login.php");
        exit();
    }
}

$_SESSION["last_activity"] = time();