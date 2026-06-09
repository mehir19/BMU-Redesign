<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $article_id = intval($_POST['article_id']);
    $dir = "uploads/requirements/";
    
    $copyright_path = $dir . time() . "_copy_" . basename($_FILES["copyright_doc"]["name"]);
    $payment_path = $dir . time() . "_pay_" . basename($_FILES["payment_receipt"]["name"]);

    if (move_uploaded_file($_FILES["copyright_doc"]["tmp_name"], $copyright_path) && 
        move_uploaded_file($_FILES["payment_receipt"]["tmp_name"], $payment_path)) {
        
        $sql = "INSERT INTO processed_documents (article_id, copyright_file, payment_file) 
                VALUES ('$article_id', '$copyright_path', '$payment_path')
                ON DUPLICATE KEY UPDATE copyright_file='$copyright_path', payment_file='$payment_path'";
        
        if ($conn->query($sql) === TRUE) {
            echo "Copyright and Payment receipts recorded successfully.";
        }
    } else {
        echo "Error transferring files.";
    }
}
?>