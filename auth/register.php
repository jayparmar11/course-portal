<?php
// Register PHP Placeholder

require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = 'student';

    if (empty($name) || empty($email) || empty($password)) {
        echo "<script>alert('All fields are required.'); window.location.href='register.html';</script>";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.'); window.location.href='register.html';</script>";
        exit;
    }

    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $db->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('ssss', $name, $email, $passwordHash, $role);

    try {
        $stmt->execute();
        header('Location: login.html');
        exit;
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() === 1062) { // Duplicate entry error code
            echo "<script>alert('This email is already registered. Please use a different email.'); window.location.href='register.html';</script>";
        } else {
            echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href='register.html';</script>";
        }
        exit;
    }
}
