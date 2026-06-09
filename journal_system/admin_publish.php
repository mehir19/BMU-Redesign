<?php
include 'db.php';
$_SESSION['role'] = 'Admin';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['role'] == 'Admin') {
    $article_id = intval($_POST['article_id']);
    
    // Verify files
    $conn->query("UPDATE processed_documents SET is_verified = TRUE WHERE article_id = $article_id");
    
    // Upload Published Formatted PDF
    $target_dir = "uploads/published/";
    $final_file = $target_dir . time() . "_final_" . basename($_FILES["final_pdf"]["name"]);

    if (move_uploaded_file($_FILES["final_pdf"]["tmp_name"], $final_file)) {
        $conn->query("UPDATE articles SET status = 'Published', formatted_file_path = '$final_file' WHERE id = $article_id");
        echo "Article successfully verified and Published live!";
    }
}

// Display Pending Publication articles
$pending = $conn->query("SELECT a.id, a.title, d.copyright_file, d.payment_file FROM articles a 
                         JOIN processed_documents d ON a.id = d.article_id WHERE a.status = 'Accepted'");
?>

<h2>Admin Panel: Verify & Publish</h2>
<?php while($row = $pending->fetch_assoc()): ?>
    <div>
        <p><strong>Article:</strong> <?= $row['title'] ?></p>
        <p><a href="<?= $row['copyright_file'] ?>" target="_blank">View Copyright Doc</a> | 
           <a href="<?= $row['payment_file'] ?>" target="_blank">View Payment Receipt</a></p>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="article_id" value="<?= $row['id'] ?>">
            <label>Upload Formatted Camera-Ready PDF:</label>
            <input type="file" name="final_pdf" accept=".pdf" required><br><br>
            <button type="submit">Verify Assets & Publish</button>
        </form>
    </div>
    <hr>
<?php endwhile; ?>