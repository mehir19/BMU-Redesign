<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $intended_role = $_POST['intended_role'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // 1. Check password hash validity
        if (password_verify($password, $user['password'])) {
            
            // 2. Multi-tier cross-check constraint validation
            if ($user['role'] !== $intended_role) {
                header("Location: index.php?login_error=Role mismatch. This account is registered as a " . $user['role'] . ".");
                exit();
            }

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Route to correct layout views
            if ($user['role'] == 'Admin') header("Location: admin_dashboard.php");
            elseif ($user['role'] == 'Reviewer') header("Location: reviewer_dashboard.php");
            else header("Location: author_dashboard.php");
            exit();
            
        } else {
            header("Location: index.php?login_error=Incorrect authentication password.");
            exit();
        }
    } else {
        header("Location: index.php?login_error=No account profile located matching that address.");
        exit();
    }
}
?>