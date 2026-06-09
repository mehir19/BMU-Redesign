<?php
include 'db.php';
// Route Guard: Access allowed only for logged-in Authors
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Author') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard Portal Context</title>
    <link rel="stylesheet" href="css/backend.css">
</head>
<body>
    <div class="main">
        <div class="container">
            <div class="sidebar">
                <h3>Welcome, <?= htmlspecialchars($_SESSION['username']); ?></h3>
                <p><small>Role: Author</small></p><hr>
                <a href="?page=submit">Submit New Article</a>
                <a href="?page=status">Track Status & Upload Docs</a>
                <a href="?page=viewarticles">View My Articles</a>
                <a href="logout.php" style="color: #e74c3c;">Sign Out</a>
            </div>
            <div class="main-content">
                <?php
                // Dynamic UI View injection based on sidebar navigation queries
                $page = $_GET['page'] ?? 'submit';
                if ($page == 'status') {
                    include 'track_and_upload.php'; 
                } elseif ($page == 'viewarticles') {
                    include 'view_my_articles.php'; 
                } else {
                    include 'submit_article.php';
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>