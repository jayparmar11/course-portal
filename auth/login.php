<?php
// Login PHP Placeholder
require_once '../includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        header('Location: login.php?error=empty');
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
            header('Location: login.php?error=invalid');
            exit;
        }
    } else {
        header('Location: login.php?error=notfound');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script>
        function validateForm() {
            const email = document.forms['loginForm']['email'].value;
            const password = document.forms['loginForm']['password'].value;

            if (!email || !password) {
                alert('Email and password are required.');
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <h1>Login</h1>

    <?php if (isset($_GET['error'])): ?>
        <p style="color: red;">
            <?php
            switch ($_GET['error']) {
                case 'empty':
                    echo 'Email and password are required.';
                    break;
                case 'invalid':
                    echo 'Invalid password. Please try again.';
                    break;
                case 'notfound':
                    echo 'User not found. Please register first.';
                    break;
                default:
                    echo 'An unknown error occurred.';
            }
            ?>
        </p>
    <?php endif; ?>

    <form name="loginForm" action="login.php" method="POST" onsubmit="return validateForm()">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <button type="submit">Login</button>
    </form>
</body>
</html>
