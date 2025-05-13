<?php
// Start session if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Optional: Restrict by user role if needed (e.g., admin only)
if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    echo "Access denied. Admins only.";
    exit;
}
