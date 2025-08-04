<?php
require_once '../includes/header.php';
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

$successMessage = '';

// Handle course addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $faculty = trim($_POST['faculty']);

    if (!empty($title) && !empty($description) && !empty($faculty)) {
        $stmt = $db->prepare('INSERT INTO courses (title, description, faculty) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $title, $description, $faculty);
        if ($stmt->execute()) {
            $successMessage = 'Course added successfully!';
        }
    }
}

// Handle course deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $courseId = intval($_POST['course_id']);
    $stmt = $db->prepare('DELETE FROM courses WHERE id = ?');
    $stmt->bind_param('i', $courseId);
    $stmt->execute();
}

// Pagination setup
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

// Fetch total courses count for pagination
$totalCourses = $db->query('SELECT COUNT(*) AS total FROM courses')->fetch_assoc()['total'];
$totalPages = ceil($totalCourses / $limit);

// Fetch paginated courses
$stmt = $db->prepare('SELECT * FROM courses LIMIT ? OFFSET ?');
$stmt->bind_param('ii', $limit, $offset);
$stmt->execute();
$courses = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Courses</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script>
        function confirmDelete(courseId) {
            if (confirm('Are you sure you want to delete this course?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '';

                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = 'delete';
                form.appendChild(actionInput);

                const courseIdInput = document.createElement('input');
                courseIdInput.type = 'hidden';
                courseIdInput.name = 'course_id';
                courseIdInput.value = courseId;
                form.appendChild(courseIdInput);

                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</head>
<body>
    <h1>Manage Courses</h1>

    <?php if (!empty($successMessage)): ?>
        <p style="color: green;"> <?php echo $successMessage; ?> </p>
    <?php endif; ?>

    <h2>Add Course</h2>
    <form method="POST">
        <input type="hidden" name="action" value="add">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea><br>

        <label for="faculty">Faculty:</label>
        <input type="text" id="faculty" name="faculty" required><br>

        <button type="submit">Add Course</button>
    </form>

    <h2>Existing Courses</h2>
    <ul>
        <?php while ($course = $courses->fetch_assoc()): ?>
            <li>
                <strong><?php echo htmlspecialchars($course['title']); ?></strong><br>
                <?php echo htmlspecialchars($course['description']); ?><br>
                Faculty: <?php echo htmlspecialchars($course['faculty']); ?><br>
                <button onclick="confirmDelete(<?php echo $course['id']; ?>)">Delete</button>
            </li>
        <?php endwhile; ?>
    </ul>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="manage_courses.php?page=<?php echo $i; ?>" <?php if ($i === $page) echo 'class="active"'; ?>><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</body>
</html>
