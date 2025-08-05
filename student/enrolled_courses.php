<?php
require_once '../includes/header.php';
require_once '../includes/db.php';
function displayEnrolledCourses($db, $userId) {
    $enrolledCoursesStmt = $db->prepare('SELECT c.* FROM courses c JOIN enrollments e ON c.id = e.course_id WHERE e.user_id = ?');
    $enrolledCoursesStmt->bind_param('i', $userId);
    $enrolledCoursesStmt->execute();
    $enrolledCourses = $enrolledCoursesStmt->get_result();

    echo '<div class="courses-grid">';
    while ($course = $enrolledCourses->fetch_assoc()) {
        echo '<div class="course-card">';
        echo '<div class="course-header">';
        echo '<h3 class="course-title">' . htmlspecialchars($course['title']) . '</h3>';
        echo '</div>';
        echo '<div class="course-body">';
        echo '<p class="course-description">' . htmlspecialchars($course['description']) . '</p>';
        echo '<p class="course-faculty">Faculty: ' . htmlspecialchars($course['faculty']) . '</p>';
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';
}


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
    <h2>Enrolled Courses</h2>
    <div class="courses-container">
        <?php
        displayEnrolledCourses($db, $userId);
        ?>
    </div>
</body>
</html>
