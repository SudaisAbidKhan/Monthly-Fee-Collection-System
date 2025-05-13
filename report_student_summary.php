<?php
require_once 'session.php';
require_once 'db_connect.php';

// Fetch students for dropdown
$students_result = $conn->query("SELECT id, name, class FROM students ORDER BY name");

$student_id = $_GET['student_id'] ?? '';
$summary = [];

if ($student_id) {
    $stmt = $conn->prepare("SELECT month, amount, status, payment_date FROM fees WHERE student_id = ? ORDER BY month");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $summary[] = $row;
    }

    // Get student name
    $name_query = $conn->prepare("SELECT name, class FROM students WHERE id = ?");
    $name_query->bind_param("i", $student_id);
    $name_query->execute();
    $student_info = $name_query->get_result()->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Payment Summary</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="custom_css/student-summary-style.css">
</head>
<body>
<div class="summary-container">
    <h2>Student Payment Summary</h2>

    <form method="GET" class="mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-6">
                <select name="student_id" class="form-control" required>
                    <option value="">Select Student</option>
                    <?php while ($s = $students_result->fetch_assoc()): ?>
                        <option value="<?= $s['id'] ?>" <?= ($student_id == $s['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['name']) ?> (Class <?= htmlspecialchars($s['class']) ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btnback-btn">Show Summary</button>
            </div>
        </div>
    </form>

    <?php if ($student_id && $student_info): ?>
        <h4 class="text-white">Summary for <?= htmlspecialchars($student_info['name']) ?> (Class <?= htmlspecialchars($student_info['class']) ?>)</h4>
        <div class="table-responsive mt-3">
            <table class="table table-dark table-striped table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Month</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Payment Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($summary as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['month']) ?></td>
                            <td>PKR <?= number_format($item['amount'], 2) ?></td>
                            <td>
                                <span class="badge bg-<?= $item['status'] === 'paid' ? 'success' : 'danger' ?>">
                                    <?= ucfirst($item['status']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($item['payment_date']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (count($summary) === 0): ?>
                        <tr><td colspan="4" class="text-center">No payments found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <a href="dashboard.php" class="btnback">⬅️ Back to Dashboard</a>
</div>
</body>
</html>
