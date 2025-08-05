<?php
// Login PHP Placeholder
require_once '../includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        echo "<script>alert('Email and password are required.'); window.location.href='login.html';</script>";
        exit;
    }

    $stmt = $db->prepare('SELECT id, name, password_hash, role FROM users WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header('Location: ../admin/dashboard.php');
            } else {
                header('Location: ../student/dashboard.php');
            }
            exit;
        } else {
            echo "<script>alert('Invalid password. Please try again.'); window.location.href='login.html';</script>";
            exit;
        }
    } else {
        echo "<script>alert('User not found. Please register first.'); window.location.href='login.html';</script>";
        exit;
    }
}
?>
