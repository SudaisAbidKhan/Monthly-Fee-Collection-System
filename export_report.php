<?php
require_once 'session.php';
require_once 'db_connect.php';

// Handle export request
if (isset($_GET['type'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="export_report.csv"');
    $output = fopen('php://output', 'w');

    $type = $_GET['type'];

    if ($type === 'paid_unpaid_month' && isset($_GET['month'])) {
        $month = $_GET['month'];

        $students_result = $conn->query("SELECT id, name, class FROM students ORDER BY class, name");
        $paid_result = $conn->prepare("SELECT student_id FROM fees WHERE month = ? AND status = 'paid'");
        $paid_result->bind_param("s", $month);
        $paid_result->execute();
        $paid_ids = $paid_result->get_result()->fetch_all(MYSQLI_ASSOC);
        $paid_ids = array_column($paid_ids, 'student_id');

        fputcsv($output, ['Student Name', 'Class', 'Status']);
        while ($student = $students_result->fetch_assoc()) {
            $status = in_array($student['id'], $paid_ids) ? 'Paid' : 'Unpaid';
            fputcsv($output, [$student['name'], $student['class'], $status]);
        }

    } elseif ($type === 'paid_unpaid_class' && isset($_GET['class'])) {
        $class = $_GET['class'];

        $students_result = $conn->prepare("SELECT id, name FROM students WHERE class = ?");
        $students_result->bind_param("s", $class);
        $students_result->execute();
        $students = $students_result->get_result();

        $paid_result = $conn->prepare("SELECT student_id FROM fees WHERE status = 'paid'");
        $paid_result->execute();
        $paid_ids = $paid_result->get_result()->fetch_all(MYSQLI_ASSOC);
        $paid_ids = array_column($paid_ids, 'student_id');

        fputcsv($output, ['Student Name', 'Class', 'Status']);
        while ($student = $students->fetch_assoc()) {
            $status = in_array($student['id'], $paid_ids) ? 'Paid' : 'Unpaid';
            fputcsv($output, [$student['name'], $class, $status]);
        }

    } else {
        fputcsv($output, ['Invalid or missing parameters.']);
    }

    fclose($output);
    exit;
}

// If no export requested, show export form:
$classes_result = $conn->query("SELECT DISTINCT class FROM students ORDER BY class");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Export Reports</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="custom_css/export-style.css">
</head>
<body>
<div class="export-container">
    <h2>Export Reports</h2>

    <!-- Export by Month -->
    <form action="export_report.php" method="GET" class="glass-card mb-5">
        <h5 class="form-heading">Export by Month</h5>
        <div class="mb-3">
            <label for="month" class="form-label">Month (e.g., May 2025)</label>
            <input type="text" name="month" class="form-control" required>
            <input type="hidden" name="type" value="paid_unpaid_month">
        </div>
        <button type="submit" class="btn btn-outline-light">
            <i class="bi bi-file-earmark-arrow-down-fill me-2"></i> Export by Month
        </button>
    </form>

    <!-- Export by Class -->
    <form action="export_report.php" method="GET" class="glass-card mb-5">
        <h5 class="form-heading">Export by Class</h5>
        <div class="mb-3">
            <label for="class" class="form-label">Select Class</label>
            <select name="class" class="form-select" required>
                <option value="">-- Select Class --</option>
                <?php while ($row = $classes_result->fetch_assoc()): ?>
                    <option value="<?= $row['class'] ?>"><?= htmlspecialchars($row['class']) ?></option>
                <?php endwhile; ?>
            </select>
            <input type="hidden" name="type" value="paid_unpaid_class">
        </div>
        <button type="submit" class="btn btn-outline-success">
            <i class="bi bi-filetype-csv me-2"></i> Export by Class
        </button>
    </form>

    <a href="dashboard.php" class="btnback">⬅️ Back to Dashboard</a>
</div>
</body>
</html>
