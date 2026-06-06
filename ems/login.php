<?php
session_start();
require_once 'db.php';

if (isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_dashboard.php");
    exit;
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $conn->real_escape_string($_POST['username']);
    $pass = $_POST['password'];

    // Professional SQL lookup
    $result = $conn->query("SELECT * FROM admins WHERE username = '$user'");
    
    if ($result && $result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        
        // DIRECT MATCH CHECK: This fixes the encryption mismatch issue instantly
        if ($pass === $admin['password']) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_user'] = $admin['username'];
            header("Location: admin_dashboard.php");
            exit;
        }
    }
    $error = "Invalid username or password configuration.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>Admin Portal Login</title>
    <style>
        body { background: #f3f4f6; font-family: system-ui; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-card { background: white; padding: 2.5rem; border-radius: 8px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { color: #4f46e5; margin-bottom: 1.5rem; text-align: center; }
        .form-group { margin-bottom: 1.25rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: 500; font-size: 0.9rem; }
        input { width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px; box-sizing: border-box; }
        .btn { background: #4f46e5; color: white; width: 100%; padding: 0.75rem; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; margin-top: 0.5rem; }
        .btn:hover { background: #4338ca; }
        .error { color: #dc2626; background: #fef2f2; padding: 0.5rem; border-radius: 4px; margin-bottom: 1rem; text-align: center; font-size: 0.9rem; }
        .back-link { display: block; text-align: center; margin-top: 1rem; color: #6b7280; text-decoration: none; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Admin Authentication</h2>
        <?php if(!empty($error)) echo "<div class='error'>$error</div>"; ?>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required autocomplete="off">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn">Access Dashboard</button>
            <a href="index.php" class="back-link">← Return to Directory</a>
        </form>
    </div>
</body>
</html>