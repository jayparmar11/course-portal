<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: auth/register.html');
    exit;
}

if ($_SESSION['user_role'] === 'admin') {
    header('Location: admin/dashboard.php');
    exit;
} elseif ($_SESSION['user_role'] === 'student') {
    header('Location: student/dashboard.php');
    exit;
} else {
    // Handle unexpected roles or errors
    echo "Invalid user role.";
    exit;
}
?>
