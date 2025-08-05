<?php
require_once '../includes/header.php';
require_once '../includes/db.php';
function displayAvailableCourses($db, $userId, $page = 1, $limit = 5) {
    $offset = ($page - 1) * $limit;

    // Fetch total courses count for pagination
    $countStmt = $db->prepare('SELECT COUNT(*) AS total FROM courses WHERE id NOT IN (SELECT course_id FROM enrollments WHERE user_id = ?)');
    $countStmt->bind_param('i', $userId);
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $total = $countResult->fetch_assoc()['total'];
    $totalPages = ceil($total / $limit);

    // Fetch paginated courses
    $availableCoursesStmt = $db->prepare('SELECT * FROM courses WHERE id NOT IN (SELECT course_id FROM enrollments WHERE user_id = ?) LIMIT ? OFFSET ?');
    $availableCoursesStmt->bind_param('iii', $userId, $limit, $offset);
    $availableCoursesStmt->execute();
    $availableCourses = $availableCoursesStmt->get_result();

    echo '<div class="courses-grid">';
    while ($course = $availableCourses->fetch_assoc()) {
        echo '<div class="course-card">';
        echo '<div class="course-header">';
        echo '<h3 class="course-title">' . htmlspecialchars($course['title']) . '</h3>';
        echo '</div>';
        echo '<div class="course-body">';
        echo '<p class="course-description">' . htmlspecialchars($course['description']) . '</p>';
        echo '<p class="course-faculty">Faculty: ' . htmlspecialchars($course['faculty']) . '</p>';
        echo '</div>';
        echo '<div class="course-footer">';
        echo '<button class="enroll-button" onclick="enroll(' . $course['id'] . ')">Enroll</button>';
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';

    // Pagination controls
    if ($totalPages > 1) {
        echo '<div class="pagination">';
        for ($i = 1; $i <= $totalPages; $i++) {
            echo '<button onclick="loadPage(' . $i . ')">' . $i . '</button> ';
        }
        echo '</div>';
    }
}

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    header('Location: ../auth/login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Available Courses</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body><h2>Available Courses</h2>
    <div class="courses-container">
        <?php
        displayAvailableCourses($db, $userId, $page);
        ?>
    </div>

    <!-- Modal Overlay -->
    <div id="modalOverlay" onclick="closeModal()"></div>

    <!-- Modal Structure -->
    <div id="courseModal" class="modal">
        <h2 id="modalTitle"></h2>
        <p id="modalDescription"></p>
        <p id="modalFaculty"></p>
        <button onclick="closeModal()" class="btn">Close</button>
    </div>

    <script>
        function loadPage(page) {
            window.location.href = `courses.php?page=${page}`;
        }

        function viewDetails(courseId) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `fetch_course_details_ajax.php?course_id=${courseId}`, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const course = JSON.parse(xhr.responseText);
                    document.getElementById('modalTitle').innerText = course.title;
                    document.getElementById('modalDescription').innerText = course.description;
                    document.getElementById('modalFaculty').innerText = `Faculty: ${course.faculty}`;
                    document.getElementById('modalOverlay').style.display = 'block';
                    document.getElementById('courseModal').style.display = 'block';
                } else {
                    alert('Error fetching course details.');
                }
            };
            xhr.send();
        }

        function closeModal() {
            document.getElementById('modalOverlay').style.display = 'none';
            document.getElementById('courseModal').style.display = 'none';
        }

        function enroll(courseId) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'enroll_ajax.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert('Enrolled successfully!');
                    location.reload();
                } else {
                    alert('Error enrolling in course.');
                }
            };
            xhr.send('course_id=' + courseId);
        }
    </script>
</body>
</html>
