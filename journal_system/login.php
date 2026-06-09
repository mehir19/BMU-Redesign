<?php
include 'db.php'; // Contains session_start() and $conn

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password']; // Raw password string from form

    // Fetch user from DB
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Verify password (Assumes you used password_hash when creating users)
        if (password_verify($password, $user['password'])) {
            // Store credentials in Session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect users dynamically based on their system tier
            if ($user['role'] == 'Admin') {
                header("Location: admin_dashboard.php");
            } elseif ($user['role'] == 'Reviewer') {
                header("Location: reviewer_dashboard.php");
            } else {
                header("Location: author_dashboard.php");
            }
            exit();
        } else {
            $error = "Invalid password credentials.";
        }
    } else {
        $error = "No account found associated with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Journal System - Login</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 320px; }
        input, button { width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background: #007bff; color: white; border: none; cursor: pointer; font-weight: bold; }
        button:hover { background: #0056b3; }
        .error { color: red; font-size: 14px; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Journal Login</h2>
        <?php if(!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <form action="" method="POST">
            <label>Email Address</label>
            <input type="email" name="email" required placeholder="name@journal.com">
            
            <label>Password</label>
            <input type="password" name="password" required placeholder="••••••••">
            
            <button type="submit">Sign In</button>
        </form>
    </div>
</body>
</html>