<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
?>

<div class="header">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
    <a href="../auth/logout.php" class="logout-button">Logout</a>
</div>

<style>
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #f4f4f4;
        padding: 10px 20px;
        border-bottom: 1px solid #ddd;
    }

    .header h1 {
        margin: 0;
    }

    .logout-button {
        text-decoration: none;
        color: #007BFF;
        font-weight: bold;
    }

    .logout-button:hover {
        text-decoration: underline;
    }
</style>
