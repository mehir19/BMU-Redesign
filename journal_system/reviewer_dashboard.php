<?php
include 'db.php';

// Route Guard Validation Verification
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Reviewer') {
    header("Location: index.php?login_error=Unauthorized entry access points restricted.");
    exit();
}

$reviewer_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reviewer Portal Hub</title>
    <link rel="stylesheet" href="css/backend.css">
</head>
<body>

    <div class="sidebar">
        <h3>Welcome, <?= htmlspecialchars($_SESSION['username']); ?></h3>
        <p>Role: Peer Review Investigator</p>
        <hr style="border: 0; border-top: 1px solid #34495e; margin-bottom: 15px;">
        <a href="reviewer_dashboard.php" class="active">Assigned Review Pool</a>
        <a href="logout.php" style="color: #e74c3c; margin-top: 30px;">Sign Out</a>
    </div>

    <div class="main-content">
        <h2>Manuscript Repository Awaiting Peer Verification</h2>
        <p style="color:#718096; font-size:14px; margin-bottom:25px;">Provide critiques for freshly submitted articles below to advance them in the validation cycle.</p>
        
        <?php
        // Process inline review submission critiques
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {
            $article_id = intval($_POST['article_id']);
            $review_text = $conn->real_escape_string($_POST['review_text']);

            $sql = "INSERT INTO reviews (article_id, reviewer_id, review_text, admin_review_status) 
                    VALUES ('$article_id', '$reviewer_id', '$review_text', 'Pending')";
            
            if ($conn->query($sql) === TRUE) {
                // Update the manuscript status so it reflects active review operations
                $conn->query("UPDATE articles SET status = 'Under Review' WHERE id = $article_id");
                echo "<p style='color: green; background: #f0fff4; padding: 12px; border-left: 4px solid #38a169; margin-bottom: 20px; font-weight:600;'>✓ Review analysis saved successfully and queued for Editorial verification!</p>";
            } else {
                echo "<p style='color: red; background:#fff5f5; padding:12px;'>Database error: " . $conn->error . "</p>";
            }
        }

        // Fetch all articles currently in the evaluation pipeline
        $query = "SELECT * FROM articles WHERE status IN ('Submitted', 'Under Review') ORDER BY id DESC";
        $articles = $conn->query($query);

        if ($articles && $articles->num_rows > 0):
            while($row = $articles->fetch_assoc()):
        ?>
                <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 25px; margin-bottom: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
                    <h3 style="color:#2b6cb0; margin-bottom:10px;"><?= htmlspecialchars($row['title']) ?></h3>
                    <p style="font-size: 13px; margin-bottom: 12px; color:#718096;">
                        Current State: <strong style="color:#dd6b20; text-transform:uppercase;"><?= $row['status'] ?></strong>
                    </p>
                    <p style="color: #4a5568; margin-bottom: 15px; font-size:15px; background:#f7fafc; padding:15px; border-radius:4px;">
                        <strong>Abstract Summary:</strong><br><?= nl2br(htmlspecialchars($row['abstract'])) ?>
                    </p>
                    
                    <p style="margin-bottom: 20px;">
                        <?php if (file_exists($row['file_path'])): ?>
                            <a href="<?= htmlspecialchars($row['file_path']) ?>" target="_blank" style="color: #3182ce; font-weight: 600; text-decoration: none;">📥 Download Original Research Document (PDF)</a>
                        <?php else: ?>
                            <span style="color:#e53e3e; font-weight:600; font-size:14px;">⚠️ Original PDF Source was removed from local folder</span>
                        <?php endif; ?>
                    </p>
                    
                    <form action="" method="POST" style="border-top: 1px dashed #e2e8f0; padding-top: 20px;">
                        <input type="hidden" name="article_id" value="<?= $row['id'] ?>">
                        <label style="display:block; font-weight: 600; font-size: 14px; margin-bottom:8px; color: #2c3e50;">Provide Your Critique / Peer Verification Notes:</label>
                        <textarea name="review_text" required placeholder="Type your full technical breakdown regarding methodologies, structural logic, and academic accuracy metrics here..." style="width:100%; height:110px; padding:12px; border:1px solid #cbd5e1; border-radius:4px; margin-bottom:12px; font-size:14px;"></textarea>
                        <button type="submit" name="submit_review" class="btn-action" style="background:#38a169;">Submit Review Assessment</button>
                    </form>
                </div>
        <?php 
            endwhile; 
        else: 
        ?>
            <div style="background:#fff; text-align:center; padding:40px; color:#718096; border:1px solid #e2e8f0; border-radius:6px;">
                Currently, no manuscripts require evaluation in the review pool.
            </div>
        <?php endif; ?>
    </div>

</body>
</html>