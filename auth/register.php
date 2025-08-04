<?php
// Register PHP Placeholder

require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = 'student';

    if (empty($name) || empty($email) || empty($password)) {
        die('All fields are required.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Invalid email format.');
    }

    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $db->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('ssss', $name, $email, $passwordHash, $role);

    if ($stmt->execute()) {
        header('Location: login.html');
        exit;
    } else {
        die('Error: ' . $stmt->error);
    }
}
