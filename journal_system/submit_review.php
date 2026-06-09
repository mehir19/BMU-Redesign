<?php
include 'db.php';
$_SESSION['user_id'] = 2; // Mock Reviewer ID
$_SESSION['role'] = 'Reviewer';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && ($_SESSION['role'] == 'Reviewer' || $_SESSION['role'] == 'Admin')) {
    $article_id = intval($_POST['article_id']);
    $review_text = $conn->real_escape_string($_POST['review_text']);
    $reviewer_id = $_SESSION['user_id'];

    $sql = "INSERT INTO reviews (article_id, reviewer_id, review_text) VALUES ('$article_id', '$reviewer_id', '$review_text')";
    if ($conn->query($sql) === TRUE) {
        // Automatically change status to Under Review
        $conn->query("UPDATE articles SET status = 'Under Review' WHERE id = '$article_id'");
        echo "Review submitted successfully!";
    }
}
?>

<form action="" method="POST">
    <h2>Submit Review</h2>
    <input type="number" name="article_id" placeholder="Article ID" required><br><br>
    <textarea name="review_text" placeholder="Write your review critiques here..." required></textarea><br><br>
    <button type="submit">Submit Review</button>
</form>