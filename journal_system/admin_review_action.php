<?php
include 'db.php';
$_SESSION['role'] = 'Admin'; // Mock Admin

if (isset($_GET['review_id']) && isset($_GET['action']) && $_SESSION['role'] == 'Admin') {
    $review_id = intval($_GET['review_id']);
    $action = $_GET['action']; // 'Accept' or 'Reject'
    
    // Fetch associated article
    $res = $conn->query("SELECT article_id FROM reviews WHERE id = $review_id");
    $review = $res->fetch_assoc();
    $article_id = $review['article_id'];

    if ($action == 'Accept') {
        $conn->query("UPDATE reviews SET admin_review_status = 'Accept' WHERE id = $review_id");
        $conn->query("UPDATE articles SET status = 'Accepted' WHERE id = $article_id");
        echo "Review accepted. Article status changed to 'Accepted'.";
    } elseif ($action == 'Reject') {
        $conn->query("UPDATE reviews SET admin_review_status = 'Reject' WHERE id = $review_id");
        // Article status stays 'Under Review' or reverts back to 'Submitted' so reviewers can pick it up again
        $conn->query("UPDATE articles SET status = 'Submitted' WHERE id = $article_id");
        echo "Review rejected. Article remains available for fresh reviews.";
    }
}
?>