<?php
include 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Author') { 
    exit("Unauthorized Access Workspace Blocked."); 
}

$author_id = $_SESSION['user_id'];

// SQL Query to pull down all records matching the active log-in identity
$result = $conn->query("SELECT * FROM articles WHERE author_id = $author_id ORDER BY id DESC");
?>

<h2>My Structural Submission Ledger</h2>
<p style="color:#718096; font-size:14px; margin-bottom: 20px;">Review the pipeline states of your submitted research blueprints below:</p>

<table class="data-table" style="width:100%; background:#fff; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
    <thead>
        <tr>
            <th>Manuscript ID</th>
            <th>Research Paper Title</th>
            <th>Submission Date</th>
            <th>Workflow Processing Status</th>
            <th>Original Source Document</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): 
                $clean_status = str_replace(' ', '-', $row['status']);
            ?>
            <tr>
                <td>#<?= $row['id'] ?></td>
                <td><strong><?= htmlspecialchars($row['title']) ?></strong></td>
                <td><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                <td>
                    <span class="status-badge status-<?= $clean_status ?>">
                        <?= htmlspecialchars($row['status']) ?>
                    </span>
                </td>
                <td>
                    <?php if (file_exists($row['file_path'])): ?>
                        <a href="<?= htmlspecialchars($row['file_path']) ?>" target="_blank" style="color:#3182ce; font-weight:600; text-decoration:none;">Open Manuscript PDF</a>
                    <?php else: ?>
                        <span style="color:#e53e3e; font-size:13px; font-weight:600;">⚠️ Source PDF Missing on Disk</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" style="text-align:center; color:#718096; padding: 30px;">You haven't uploaded any research articles yet.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>