<?php
require_once 'session.php'; // This already calls session_start() and checks session
?>

<?php
// dashboard.php

// Do NOT call session_start() again here

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="custom_css/glass-dashborad.css">
</head>
<body>
    <nav>
        <a class="navbar-brand" href="#">Fee Collection System</a>
        <div class="d-flex">
            <span class="navbar-text">Welcome, <?= htmlspecialchars($_SESSION['username']) ?></span>
            <a class="btn" href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h2>Admin Dashboard</h2>
        <div class="dashboard-grid">
            <a href="add_student.php">Add Student</a>
            <a href="view_students.php">View Students</a>
            <a href="fee_structure.php">Set Fee Structure</a>
            <a href="record_payment.php">Record Payment</a>
            <a href="view_payments.php">View Payments</a>
            <a href="report_paid_unpaid.php">Paid/Unpaid Report</a>
            <a href="report_student_summary.php">Student Summary</a>
            <a href="export_report.php">Export Reports</a>
        </div>
    </div>
</body>
</html>

