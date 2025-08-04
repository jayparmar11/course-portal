<?php
require_once '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    http_response_code(403);
    echo 'Unauthorized';
    exit;
}

$userId = $_SESSION['user_id'];
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

// Fetch feedback entries
$stmt = $db->prepare('SELECT f.*, c.title AS course_title FROM feedback f JOIN courses c ON f.course_id = c.id WHERE f.user_id = ? LIMIT ? OFFSET ?');
$stmt->bind_param('iii', $userId, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Fetch total feedback count for pagination
$countStmt = $db->prepare('SELECT COUNT(*) AS total FROM feedback WHERE user_id = ?');
$countStmt->bind_param('i', $userId);
$countStmt->execute();
$countResult = $countStmt->get_result();
$total = $countResult->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

while ($feedback = $result->fetch_assoc()): ?>
    <div>
        <h3><?php echo htmlspecialchars($feedback['course_title']); ?></h3>
        <p>Ratings: Teaching: <?php echo json_decode($feedback['ratings'])->teaching; ?>, Interaction: <?php echo json_decode($feedback['ratings'])->interaction; ?>, Materials: <?php echo json_decode($feedback['ratings'])->materials; ?>, Overall: <?php echo json_decode($feedback['ratings'])->overall; ?></p>
        <p>Comment: <?php echo htmlspecialchars($feedback['comment']); ?></p>
        <p>Submitted on: <?php echo htmlspecialchars($feedback['created_at']); ?></p>
    </div>
<?php endwhile; ?>

<?php if ($totalPages > 1): ?>
<div>
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <button onclick="loadFeedback(<?php echo $i; ?>)"><?php echo $i; ?></button>
    <?php endfor; ?>
</div>
<?php endif; ?>
