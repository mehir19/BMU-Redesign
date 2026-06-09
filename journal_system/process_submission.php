<?php
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Author') {
    header("Location: author_dashboard.php");
    exit();
}

$title = $conn->real_escape_string($_POST['title']);
$abstract = $conn->real_escape_string($_POST['abstract']);
$target_dir = "uploads/manuscripts/";

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
        // Safe PRG redirect to clear form state parameters
        header("Location: author_dashboard.php?page=submit&success=1");
        exit();
    } else {
        header("Location: author_dashboard.php?page=submit&error=" . urlencode($conn->error));
        exit();
    }
} else {
    header("Location: author_dashboard.php?page=submit&error=FileSystemUploadFailure");
    exit();
}
?>