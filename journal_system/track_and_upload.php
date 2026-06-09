<?php
include 'db.php';
$_SESSION['user_id'] = 1; // Author

$author_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM articles WHERE author_id = $author_id");

echo "<h2>Your Submitted Articles & Status</h2>";

while($row = $result->fetch_assoc()) {
    echo "<table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
                <tr>
                    <td>{$row['title']} </td>
                    <td> {$row['status']} </td>
                </tr>
        </tbody>
    </table>";
    
    if ($row['status'] == 'Accepted') {
        echo 'Query processing forms:
        <form action="upload_requirements.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="article_id" value="'.$row['id'].'">
            <label>Upload Copyright Doc (PDF):</label> <input type="file" name="copyright_doc" accept=".pdf" required><br>
            <label>Upload Payment Receipt:</label> <input type="file" name="payment_receipt" accept="image/*,.pdf" required><br>
            <button type="submit">Submit Final Documents</button>
        </form><br>';
    }
    }
?>