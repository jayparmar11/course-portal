<?php
require_once '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    http_response_code(403);
    echo 'Unauthorized';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['feedback_id'])) {
    $feedbackId = intval($_POST['feedback_id']);
    $userId = $_SESSION['user_id'];

    $stmt = $db->prepare('DELETE FROM feedback WHERE id = ? AND user_id = ?');
    $stmt->bind_param('ii', $feedbackId, $userId);

    if ($stmt->execute()) {
        http_response_code(200);
        echo 'Feedback deleted successfully';
    } else {
        http_response_code(500);
        echo 'Error deleting feedback';
    }
} else {
    http_response_code(400);
    echo 'Invalid request';
}
