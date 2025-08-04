<?php
// Manage Students Placeholder
require_once '../includes/header.php';
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

// Handle student deactivation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'deactivate') {
    $studentId = intval($_POST['student_id']);
    $stmt = $db->prepare('UPDATE users SET active = 0 WHERE id = ? AND role = "student"');
    $stmt->bind_param('i', $studentId);
    $stmt->execute();
}

// Fetch all students
$students = $db->query('SELECT id, name, email, active FROM users WHERE role = "student"');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Students</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script>
        function confirmDeactivate(studentId) {
            if (confirm('Are you sure you want to deactivate this student?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '';

                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = 'deactivate';
                form.appendChild(actionInput);

                const studentIdInput = document.createElement('input');
                studentIdInput.type = 'hidden';
                studentIdInput.name = 'student_id';
                studentIdInput.value = studentId;
                form.appendChild(studentIdInput);

                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</head>
<body>
    <h1>Manage Students</h1>

    <h2>Student List</h2>
    <ul>
        <?php while ($student = $students->fetch_assoc()): ?>
            <li>
                <strong><?php echo htmlspecialchars($student['name']); ?></strong><br>
                Email: <?php echo htmlspecialchars($student['email']); ?><br>
                Status: <?php echo $student['active'] ? 'Active' : 'Deactivated'; ?><br>
                <?php if ($student['active']): ?>
                    <button onclick="confirmDeactivate(<?php echo $student['id']; ?>)">Deactivate</button>
                <?php endif; ?>
            </li>
        <?php endwhile; ?>
    </ul>
</body>
</html>
