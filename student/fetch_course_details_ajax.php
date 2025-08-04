<?php
require_once '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    http_response_code(403);
    echo 'Unauthorized';
    exit;
}

if (isset($_GET['course_id'])) {
    $courseId = intval($_GET['course_id']);

    $stmt = $db->prepare('SELECT title, description, faculty FROM courses WHERE id = ?');
    $stmt->bind_param('i', $courseId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $course = $result->fetch_assoc();
        echo json_encode($course);
    } else {
        http_response_code(404);
        echo 'Course not found';
    }
} else {
    http_response_code(400);
    echo 'Invalid request';
}
