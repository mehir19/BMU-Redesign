<?php
include 'db.php';
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Guard against duplicate accounts
    $check = $conn->query("SELECT id FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        $msg = "<p style='color:red;'>Email already exists!</p>";
    } else {
        // Explicitly enforce the 'Author' role for public signups
        $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', 'Author')";
        if ($conn->query($sql) === TRUE) {
            $msg = "<p style='color:green;'>Registration successful! You can now log in via the homepage dropdown.</p>";
        }
    }
}
?>
<div style="max-width:400px; margin:50px auto; padding:30px; background:#fff; border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.05); font-family:sans-serif;">
    <h2>Author Registration</h2><br>
    <?= $msg ?>
    <form action="" method="POST">
        <label style="display:block; margin-bottom:5px;">Full Name</label>
        <input type="text" name="username" required style="width:100%; padding:10px; margin-bottom:15px;"><br>
        
        <label style="display:block; margin-bottom:5px;">Email Address</label>
        <input type="email" name="email" required style="width:100%; padding:10px; margin-bottom:15px;"><br>
        
        <label style="display:block; margin-bottom:5px;">Secure Password</label>
        <input type="password" name="password" required style="width:100%; padding:10px; margin-bottom:20px;"><br>
        
        <button type="submit" style="width:100%; background:#2b6cb0; color:#fff; border:none; padding:12px; font-weight:bold; cursor:pointer;">Register Account</button>
    </form>
    <br><a href="index.php" style="color:#2b6cb0; text-decoration:none; font-size:14px;">&larr; Back to Homepage Portal</a>
</div>