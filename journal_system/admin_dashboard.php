<?php
include 'db.php';
// Route Guard: Access allowed only for Admins
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: index.php");
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
    <div class="sidebar">
        <h2>Journal Admin</h2>
        <p>User: <?= htmlspecialchars($_SESSION['username']); ?></p><hr style="border:0; border-top:1px solid #34495e; margin-bottom:15px;">
        
        <?php $view = $_GET['view'] ?? 'reviews'; ?>
        <a href="?view=reviews" class="<?= $view == 'reviews' ? 'active' : '' ?>">Verify Peer Reviews</a>
        <a href="?view=publish" class="<?= $view == 'publish' ? 'active' : '' ?>">Journal Management (Publish)</a>
        <a href="?view=roles" class="<?= $view == 'roles' ? 'active' : '' ?>">Manage Users & Roles ⚙</a>
        <a href="logout.php" style="color: #ff6b6b; margin-top: 30px;">Logout</a>
    </div>

    <div class="main-content">
        <?php
        if ($view == 'publish') {
            include 'admin_publish.php';
        } elseif ($view == 'roles') {
            // INJECTED ROLE PROMOTION UTILITY PANEL
            include 'admin_roles.php'; 
        } else {
            echo '<h2>Pending Reviews Evaluation</h2><br>';
            $res = $conn->query("SELECT r.id, r.review_text, a.title FROM reviews r JOIN articles a ON r.article_id = a.id WHERE r.admin_review_status = 'Pending'");
            if ($res->num_rows == 0) echo "<p style='color:#718096;'>No reviews are currently awaiting validation action.</p>";
            while($item = $res->fetch_assoc()) {
                echo "<div style='border:1px solid #e2e8f0; background:#fff; padding:20px; margin-bottom:15px; border-radius:6px;'>
                        <h4>Article: " . htmlspecialchars($item['title']) . "</h4>
                        <p style='margin:10px 0; color:#4a5568;'>Reviewer Critique: " . htmlspecialchars($item['review_text']) . "</p>
                        <a href='admin_review_action.php?review_id={$item['id']}&action=Accept' style='color:#2f855a; font-weight:600; text-decoration:none;'>[✓ Accept Review & Article]</a> &nbsp;&nbsp;|&nbsp;&nbsp; 
                        <a href='admin_review_action.php?review_id={$item['id']}&action=Reject' style='color:#c53030; font-weight:600; text-decoration:none;'>[✕ Reject Review]</a>
                      </div>";
            }
        }
        ?>
    </div>
</body>
</html>