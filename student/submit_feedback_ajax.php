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
    $ratings = json_encode($_POST['ratings']);
    $comment = trim($_POST['comment']);

    $stmt = $db->prepare('INSERT INTO feedback (user_id, course_id, ratings, comment) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('iiss', $userId, $courseId, $ratings, $comment);

    if ($stmt->execute()) {
        http_response_code(200);
        echo 'Feedback submitted successfully';
    } else {
        http_response_code(500);
        echo 'Error submitting feedback';
    }
} else {
    http_response_code(405);
    echo 'Method not allowed';
}