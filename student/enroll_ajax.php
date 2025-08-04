<?php
require_once '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    http_response_code(403);
    echo 'Unauthorized';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $courseId = intval($_POST['course_id']);

    $stmt = $db->prepare('INSERT INTO enrollments (user_id, course_id) VALUES (?, ?)');
    $stmt->bind_param('ii', $userId, $courseId);

    if ($stmt->execute()) {
        http_response_code(200);
        echo 'Enrolled successfully';
    } else {
        http_response_code(500);
        echo 'Error enrolling in course';
    }
} else {
    http_response_code(405);
    echo 'Method not allowed';
}