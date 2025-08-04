<?php
require_once '../includes/header.php';
require_once '../includes/db.php';


if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    header('Location: ../auth/login.php');
    exit;
}

$userId = $_SESSION['user_id'];

// Fetch enrolled courses
$stmt = $db->prepare('SELECT c.id, c.title FROM courses c JOIN enrollments e ON c.id = e.course_id WHERE e.user_id = ?');
$stmt->bind_param('i', $userId);
$stmt->execute();
$enrolledCourses = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Submit Feedback</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border: 1px solid black;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
    </style>
    <script>
        function openFeedbackForm(courseId, courseTitle) {
            document.getElementById('courseName').innerText = courseTitle;
            document.getElementById('courseId').value = courseId;
            document.getElementById('modalOverlay').style.display = 'block';
            document.getElementById('feedbackModal').style.display = 'block';
        }

        function closeFeedbackForm() {
            document.getElementById('modalOverlay').style.display = 'none';
            document.getElementById('feedbackModal').style.display = 'none';
        }

        function submitFeedback() {
            const form = document.getElementById('feedbackForm');
            const formData = new FormData(form);

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'submit_feedback_ajax.php', true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert('Feedback submitted successfully!');
                    closeFeedbackForm();
                } else {
                    alert('Error submitting feedback.');
                }
            };
            xhr.send(formData);
        }
    </script>
</head>
<body>
    <h1>Submit Feedback</h1>

    <ul>
        <?php while ($course = $enrolledCourses->fetch_assoc()): ?>
            <li>
                <?php echo htmlspecialchars($course['title']); ?>
                <button onclick="openFeedbackForm(<?php echo $course['id']; ?>, '<?php echo htmlspecialchars($course['title']); ?>')">Give Feedback</button>
            </li>
        <?php endwhile; ?>
    </ul>

    <!-- Modal Overlay -->
    <div id="modalOverlay" class="modal-overlay" onclick="closeFeedbackForm()"></div>

    <!-- Feedback Modal -->
    <div id="feedbackModal" class="modal">
        <h2>Submit Feedback for <span id="courseName"></span></h2>
        <form id="feedbackForm">
            <input type="hidden" id="courseId" name="course_id">

            <label for="teaching">Teaching:</label>
            <input type="number" id="teaching" name="ratings[teaching]" min="1" max="5" required><br>

            <label for="interaction">Interaction:</label>
            <input type="number" id="interaction" name="ratings[interaction]" min="1" max="5" required><br>

            <label for="materials">Materials:</label>
            <input type="number" id="materials" name="ratings[materials]" min="1" max="5" required><br>

            <label for="overall">Overall:</label>
            <input type="number" id="overall" name="ratings[overall]" min="1" max="5" required><br>

            <label for="comment">Comment:</label>
            <textarea id="comment" name="comment" required></textarea><br>

            <button type="button" onclick="submitFeedback()">Submit</button>
            <button type="button" onclick="closeFeedbackForm()">Cancel</button>
        </form>
    </div>
</body>
</html>
