<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$dashboardLink = ($_SESSION['user_role'] === 'admin') ? '../admin/dashboard.php' : '../student/dashboard.php';
?>

<div class="header">
    <a href="<?php echo $dashboardLink; ?>" class="logo">Course Portal</a>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
    <a href="../auth/logout.php" class="logout-button">Logout</a>
    <link rel="stylesheet" href="assets/css/style.css">
</div>