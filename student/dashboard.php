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
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <div class="helpful-links">
      <h2>Helpful Links</h2>
      <ul class="nav-links">
        <li><a href="courses.php">View Courses</a></li>
        <li><a href="enrolled_courses.php">View Enrolled Courses</a></li>
        <li><a href="feedback.php">Give Feedback</a></li>
        <li><a href="view_feedback.php">View Previous Feedback</a></li>
      </ul>
    </div>

    <script>
        function enroll(courseId) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'enroll_ajax.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert('Enrolled successfully!');
                    location.reload();
                } else {
                    alert('Error enrolling in course.');
                }
            };
            xhr.send('course_id=' + courseId);
        }
    </script>
</body>
</html>
