<?php
require_once '../includes/header.php';
require_once '../includes/db.php';
require_once '../includes/courses_list.php';

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
    <style>
        #courseModal {
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

        #modalOverlay {
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
    </script>
</head>
<body>
    <?php
    displayAvailableCourses($db, $userId, $page);
    ?>

    <!-- Modal Overlay -->
    <div id="modalOverlay" onclick="closeModal()"></div>

    <!-- Modal Structure -->
    <div id="courseModal">
        <h2 id="modalTitle"></h2>
        <p id="modalDescription"></p>
        <p id="modalFaculty"></p>
        <button onclick="closeModal()">Close</button>
    </div>

    <script>
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
