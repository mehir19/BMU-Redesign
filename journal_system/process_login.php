<?php
include 'db.php'; // Includes session_start() and connection parameter wrappers

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Basic verification filtering
    if (!isset($_POST['user_id']) || empty($_POST['password'])) {
        header("Location: index.php?login_error=Missing validation parameters.");
        exit();
    }

    $user_id = intval($_POST['user_id']);
    $password = $_POST['password'];

    // Query database for explicit account match based on selection array
    $sql = "SELECT * FROM users WHERE id = $user_id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verify standard secure hash strings
        if (password_verify($password, $user['password'])) {
            // Set up authorization session parameters
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];

            // Route user directly to their respective internal operational dashboards
            switch ($user['role']) {
                case 'Admin':
                    header("Location: admin_dashboard.php");
                    break;
                case 'Reviewer':
                    header("Location: reviewer_dashboard.php");
                    break;
                case 'Author':
                default:
                    header("Location: author_dashboard.php");
                    break;
            }
            exit();
        } else {
            header("Location: index.php?login_error=Incorrect authentication password configuration.");
            exit();
        }
    } else {
        header("Location: index.php?login_error=Target identity profile profile index mismatch.");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>