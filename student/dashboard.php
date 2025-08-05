<?php
require_once '../includes/header.php';
require_once '../includes/db.php';

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
        <div class="card-container">
            <div class="card"><a href="courses.php">View Courses</a></div>
            <div class="card"><a href="enrolled_courses.php">View Enrolled Courses</a></div>
            <div class="card"><a href="feedback.php">Give Feedback</a></div>
            <div class="card"><a href="view_feedback.php">View Previous Feedback</a></div>
        </div>
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