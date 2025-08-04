<?php
require_once '../includes/header.php';
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    header('Location: ../auth/login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

// Fetch feedback entries
$stmt = $db->prepare('SELECT f.id, f.comment, f.created_at, c.title AS course_title FROM feedback f JOIN courses c ON f.course_id = c.id WHERE f.user_id = ? LIMIT ? OFFSET ?');
$stmt->bind_param('iii', $userId, $limit, $offset);
$stmt->execute();
$feedbacks = $stmt->get_result();

// Fetch total feedback count for pagination
$countStmt = $db->prepare('SELECT COUNT(*) AS total FROM feedback WHERE user_id = ?');
$countStmt->bind_param('i', $userId);
$countStmt->execute();
$countResult = $countStmt->get_result();
$total = $countResult->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Feedback</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script>
        function deleteFeedback(feedbackId) {
            if (confirm('Are you sure you want to delete this feedback?')) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_feedback_ajax.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        alert('Feedback deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error deleting feedback.');
                    }
                };
                xhr.send('feedback_id=' + feedbackId);
            }
        }
    </script>
</head>
<body>
    <h1>View Feedback</h1>

    <ul>
        <?php while ($feedback = $feedbacks->fetch_assoc()): ?>
            <li>
                <strong><?php echo htmlspecialchars($feedback['course_title']); ?></strong><br>
                <?php echo htmlspecialchars($feedback['comment']); ?><br>
                Submitted on: <?php echo htmlspecialchars($feedback['created_at']); ?><br>
                <button onclick="deleteFeedback(<?php echo $feedback['id']; ?>)">Delete</button>
            </li>
        <?php endwhile; ?>
    </ul>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="view_feedback.php?page=<?php echo $i; ?>" <?php if ($i === $page) echo 'class="active"'; ?>><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</body>
</html>
