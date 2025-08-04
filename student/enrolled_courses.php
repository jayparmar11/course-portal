<?php
require_once '../includes/header.php';
require_once '../includes/db.php';
require_once '../includes/courses_list.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    header('Location: ../auth/login.php');
    exit;
}

$userId = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Enrolled Courses</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php
    displayEnrolledCourses($db, $userId);
    ?>
</body>
</html>
