<?php
include 'db.php';
$published = $conn->query("SELECT title, abstract, formatted_file_path, created_at FROM articles WHERE status = 'Published'");
?>

<h2>Published Issues</h2>
<?php if ($published->num_num_rows == 0) echo "No published articles found."; ?>
<?php while($row = $published->fetch_assoc()): ?>
    <div style="border: 1px solid #ccc; padding: 15px; margin-bottom: 10px;">
        <h3><?= htmlspecialchars($row['title']) ?></h3>
        <small>Published on: <?= $row['created_at'] ?></small>
        <p><?= htmlspecialchars($row['abstract']) ?></p>
        <a href="<?= $row['formatted_file_path'] ?>" download>Download Formatted PDF Document</a>
    </div>
<?php endwhile; ?>