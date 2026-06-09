<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $check = $conn->query("SELECT id FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        header("Location: index.php?action=register&login_error=Registration failure: Email link matches active profile record.");
        exit();
    } else {
        $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', 'Author')";
        if ($conn->query($sql) === TRUE) {
            header("Location: index.php?reg_success=1");
            exit();
        }
    }
}
?>