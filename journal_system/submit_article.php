<?php
include 'db.php';

// Mock session login for testing (e.g., Author ID 1)
$_SESSION['user_id'] = 1; 
$_SESSION['role'] = 'Author';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['role'] == 'Author') {
    $title = $conn->real_escape_string($_POST['title']);
    $abstract = $conn->real_escape_string($_POST['abstract']);
    
    // File Upload Handling
    $target_dir = "uploads/manuscripts/";
    // Fix: Automatically build folders if they are missing at runtime
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $file_name = time() . "_" . basename($_FILES["article_file"]["name"]);
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($_FILES["article_file"]["tmp_name"], $target_file)) {
        $author_id = $_SESSION['user_id'];
        $sql = "INSERT INTO articles (author_id, title, abstract, file_path, status) 
                VALUES ('$author_id', '$title', '$abstract', '$target_file', 'Submitted')";
        
        if ($conn->query($sql) === TRUE) {
            echo "Article submitted successfully!";
        } else {
            echo "Database Error: " . $conn->error;
        }
    } else {
        echo "Failed to upload manuscript file.";
    }
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Author') { exit("Unauthorized Access"); }

$status_msg = "";
if (isset($_GET['success'])) {
    $status_msg = "<p style='color:green; background:#f0fff4; padding:10px; border-left:4px solid #38a169; margin-bottom:15px;'>Article logged successfully into editorial system queue!</p>";
}
if (isset($_GET['error'])) {
    $status_msg = "<p style='color:red; background:#fff5f5; padding:10px; border-left:4px solid #e53e3e; margin-bottom:15px;'>" . htmlspecialchars($_GET['error']) . "</p>";
}
?>

<h2>Submit New Research Paper</h2>
<?= $status_msg ?>

<form action="process_submission.php" method="POST" enctype="multipart/form-data" class="login-form">
    <label>Manuscript Title</label>
    <input type="text" name="title" required placeholder="Enter full research paper title">
    
    <label>Abstract / Executive Summary</label>
    <textarea name="abstract" required placeholder="Type abstract introduction content here..." style="width:100%; height:120px; margin-bottom:15px; padding:10px; border:1px solid #cbd5e1; border-radius:4px;"></textarea>
    
    <label>Manuscript File (PDF format only)</label>
    <input type="file" name="article_file" accept=".pdf" required style="margin-bottom:20px;">
    
    <button type="submit" class="btn-action" style="display:block; width:auto;">Upload & Submit Document</button>
</form>