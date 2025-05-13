<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is logged in
 */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

/**
 * Redirect to login page if not authenticated
 */
function require_login() {
    if (!is_logged_in()) {
        header("Location: login.php");
        exit;
    }
}

/**
 * Sanitize output to prevent XSS
 */
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Flash message setter
 */
function set_flash($message, $type = 'success') {
    $_SESSION['flash'] = [
        'message' => $message,
        'type' => $type
    ];
}

/**
 * Flash message displayer
 */
function show_flash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        echo "<div class='alert alert-{$flash['type']}'>" . e($flash['message']) . "</div>";
        unset($_SESSION['flash']);
    }
}

/**
 * Format amount with ₹ symbol
 */
function format_currency($amount) {
    return '₹' . number_format($amount, 2);
}

/**
 * Generate a list of months for dropdowns or reports
 */
function get_month_list() {
    $months = [];
    $start = strtotime("-6 months");
    $end = strtotime("+6 months");

    for ($i = $start; $i <= $end; $i = strtotime("+1 month", $i)) {
        $months[] = date("F Y", $i); // e.g., "May 2025"
    }

    return $months;
}
