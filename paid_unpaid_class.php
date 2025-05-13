<?php
require_once 'session.php';
require_once 'db_connect.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="export_report.csv"');

$output = fopen('php://output', 'w');

$type = $_GET['type'] ?? '';

if ($type === 'paid_unpaid_month' && isset($_GET['month'])) {
    $month = $_GET['month'];

    $students_result = $conn->query("SELECT id, name, class FROM students ORDER BY class, name");

    $paid_stmt = $conn->prepare("SELECT student_id FROM fees WHERE month = ? AND status = 'paid'");
    $paid_stmt->bind_param("s", $month);
    $paid_stmt->execute();
    $paid_ids = $paid_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $paid_ids = array_column($paid_ids, 'student_id');

    fputcsv($output, ['Student Name', 'Class', 'Status']);

    while ($student = $students_result->fetch_assoc()) {
        $status = in_array($student['id'], $paid_ids) ? 'Paid' : 'Unpaid';
        fputcsv($output, [$student['name'], $student['class'], $status]);
    }

} elseif ($type === 'paid_unpaid_class' && isset($_GET['class'])) {
    $class = $_GET['class'];

    $students_stmt = $conn->prepare("SELECT id, name FROM students WHERE class = ? ORDER BY name");
    $students_stmt->bind_param("s", $class);
    $students_stmt->execute();
    $students_result = $students_stmt->get_result();

    $paid_stmt = $conn->prepare("SELECT student_id FROM fees WHERE status = 'paid'");
    $paid_stmt->execute();
    $paid_ids = $paid_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $paid_ids = array_column($paid_ids, 'student_id');

    fputcsv($output, ['Student Name', 'Class', 'Status']);

    while ($student = $students_result->fetch_assoc()) {
        $status = in_array($student['id'], $paid_ids) ? 'Paid' : 'Unpaid';
        fputcsv($output, [$student['name'], $class, $status]);
    }

} else {
    fputcsv($output, ['Invalid export parameters.']);
}

fclose($output);
exit;
