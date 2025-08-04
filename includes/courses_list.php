<?php
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

    echo '<h2>Available Courses</h2><ul>';
    while ($course = $availableCourses->fetch_assoc()) {
        echo '<li>';
        echo '<strong><a href="#" onclick="viewDetails(' . $course['id'] . ')">' . htmlspecialchars($course['title']) . '</a></strong><br>';
        echo htmlspecialchars($course['description']) . '<br>';
        echo 'Faculty: ' . htmlspecialchars($course['faculty']) . '<br>';
        echo '<button onclick="enroll(' . $course['id'] . ')">Enroll</button>';
        echo '</li>';
    }
    echo '</ul>';

    // Pagination controls
    if ($totalPages > 1) {
        echo '<div class="pagination">';
        for ($i = 1; $i <= $totalPages; $i++) {
            echo '<button onclick="loadPage(' . $i . ')">' . $i . '</button> ';
        }
        echo '</div>';
    }
}

function displayEnrolledCourses($db, $userId) {
    $enrolledCoursesStmt = $db->prepare('SELECT c.* FROM courses c JOIN enrollments e ON c.id = e.course_id WHERE e.user_id = ?');
    $enrolledCoursesStmt->bind_param('i', $userId);
    $enrolledCoursesStmt->execute();
    $enrolledCourses = $enrolledCoursesStmt->get_result();

    echo '<h2>Enrolled Courses</h2><ul>';
    while ($course = $enrolledCourses->fetch_assoc()) {
        echo '<li>';
        echo '<strong><a href="#" onclick="viewDetails(' . $course['id'] . ')">' . htmlspecialchars($course['title']) . '</a></strong><br>';
        echo '</li>';
    }
    echo '</ul>';
}
