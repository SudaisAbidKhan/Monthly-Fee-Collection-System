<?php
require_once 'session.php';
require_once 'db_connect.php';

$selected_month = $_GET['month'] ?? '';

$students = [];
$paid_ids = [];

if ($selected_month) {
    // Get all students who paid for the selected month
    $stmt = $conn->prepare("SELECT student_id FROM fees WHERE month = ? AND status = 'paid'");
    $stmt->bind_param("s", $selected_month);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $paid_ids[] = $row['student_id'];
    }
}

// Get all students
$students_result = $conn->query("SELECT id, name, class FROM students ORDER BY class, name");
while ($s = $students_result->fetch_assoc()) {
    $students[] = $s;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Paid/Unpaid Report</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="custom_css/report-style.css">
</head>
<body>
<div class="report-container">
    <h2>Paid/Unpaid Report</h2>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-6">
            <input type="text" name="month" class="form-control" placeholder="e.g., May 2025" value="<?= htmlspecialchars($selected_month) ?>" required>
        </div>
        <div class="col-md-2">
            <button class="btnback-btn">Generate</button>
        </div>
    </form>

    <?php if ($selected_month): ?>
        <h4 class="text-white">Report for: <?= htmlspecialchars($selected_month) ?></h4>
        <div class="table-responsive mt-3">
            <table class="table table-bordered table-dark table-striped">
                <thead class="table-light">
                    <tr>
                        <th>Student</th>
                        <th>Class</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= htmlspecialchars($student['name']) ?></td>
                            <td><?= htmlspecialchars($student['class']) ?></td>
                            <td>
                                <span class="badge bg-<?= in_array($student['id'], $paid_ids) ? 'success' : 'danger' ?>">
                                    <?= in_array($student['id'], $paid_ids) ? 'Paid' : 'Unpaid' ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <a href="dashboard.php" class="btnback">⬅️ Back to Dashboard</a>
</div>
</body>
</html>
