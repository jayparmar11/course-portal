<?php
// Admin Dashboard

require_once '../includes/header.php';
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

// Fetch total counts
$totalStudents = $db->query('SELECT COUNT(*) AS total FROM users WHERE role = "student"')->fetch_assoc()['total'];
$totalCourses = $db->query('SELECT COUNT(*) AS total FROM courses')->fetch_assoc()['total'];
$totalFeedback = $db->query('SELECT COUNT(*) AS total FROM feedback')->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Admin Dashboard</h1>
    <p>Total Students: <?php echo $totalStudents; ?></p>
    <p>Total Courses: <?php echo $totalCourses; ?></p>
    <p>Total Feedback: <?php echo $totalFeedback; ?></p>

    <div class="useful-links">
        <h2>Useful Links</h2>
        <ul>
            <li><a href="manage_courses.php">Manage Courses</a></li>
            <li><a href="manage_students.php">Manage Students</a></li>
            <li><a href="view_feedback.php">View Feedback</a></li>
        </ul>
    </div>
</body>
</html>
