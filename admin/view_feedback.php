<?php
// View Feedback Placeholder
require_once '../includes/header.php';
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

// Pagination and Search Logic
$search = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$tableQuery = "SELECT f.id, f.comment, f.ratings, c.title AS course_name, u.name AS student_name FROM feedback f 
               JOIN courses c ON f.course_id = c.id 
               JOIN users u ON f.user_id = u.id 
               WHERE f.comment LIKE ? 
               LIMIT ? OFFSET ?";
$tableStmt = $db->prepare($tableQuery);
$searchTerm = "%$search%";
$tableStmt->bind_param('sii', $searchTerm, $limit, $offset);
$tableStmt->execute();
$tableResult = $tableStmt->get_result();

// Count total feedback for pagination
$countQuery = "SELECT COUNT(*) AS total FROM feedback WHERE comment LIKE ?";
$countStmt = $db->prepare($countQuery);
$countStmt->bind_param('s', $searchTerm);
$countStmt->execute();
$countResult = $countStmt->get_result();
$totalFeedback = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalFeedback / $limit);

// Fetch average ratings for each course
$chartQuery = "SELECT c.title AS course_name, 
                      AVG(JSON_EXTRACT(f.ratings, '$.teaching')) AS avg_teaching,
                      AVG(JSON_EXTRACT(f.ratings, '$.interaction')) AS avg_interaction,
                      AVG(JSON_EXTRACT(f.ratings, '$.materials')) AS avg_materials,
                      AVG(JSON_EXTRACT(f.ratings, '$.overall')) AS avg_overall
               FROM feedback f
               JOIN courses c ON f.course_id = c.id
               GROUP BY c.id";
$chartResult = $db->query($chartQuery);

$chartData = [];
while ($row = $chartResult->fetch_assoc()) {
    $chartData[] = [
        'course_name' => $row['course_name'],
        'avg_teaching' => $row['avg_teaching'],
        'avg_interaction' => $row['avg_interaction'],
        'avg_materials' => $row['avg_materials'],
        'avg_overall' => $row['avg_overall']
    ];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Feedback</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>View Feedback</h1>

    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search feedback..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
    </form>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Feedback</th>
                <th>Ratings</th>
                <th>Course</th>
                <th>Student</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $tableResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['comment']); ?></td>
                    <td>
                        <?php 
                        $ratings = json_decode($row['ratings'], true);
                        echo "Teaching: " . $ratings['teaching'] . ", Interaction: " . $ratings['interaction'] . ", Materials: " . $ratings['materials'] . ", Overall: " . $ratings['overall'];
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <?php if ($totalPages > 1): ?>
    <div>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>

    <h2>Feedback Analytics</h2>

    <canvas id="feedbackChart" width="400" height="200"></canvas>

    <script>
        const chartData = <?php echo json_encode($chartData); ?>;

        const labels = chartData.map(data => data.course_name);
        const avgTeaching = chartData.map(data => data.avg_teaching);
        const avgInteraction = chartData.map(data => data.avg_interaction);
        const avgMaterials = chartData.map(data => data.avg_materials);
        const avgOverall = chartData.map(data => data.avg_overall);

        const ctx = document.getElementById('feedbackChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Teaching',
                        data: avgTeaching,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Interaction',
                        data: avgInteraction,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Materials',
                        data: avgMaterials,
                        backgroundColor: 'rgba(255, 206, 86, 0.2)',
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Overall',
                        data: avgOverall,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Average Feedback Ratings by Course'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
