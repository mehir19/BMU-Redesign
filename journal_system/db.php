<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "journal_system";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fix: Only start session if one doesn't exist yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>