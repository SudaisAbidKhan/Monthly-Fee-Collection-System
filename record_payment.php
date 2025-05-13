<?php
require_once 'session.php';
require_once 'db_connect.php';

// Fetch students
$students_result = $conn->query("SELECT id, name, class FROM students ORDER BY class, name");

// Handle payment submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $student_id = $_POST['student_id'];
    $month = $_POST['month'];
    $amount = floatval($_POST['amount']);
    $payment_date = date('Y-m-d');
    $status = 'paid';

    if ($student_id && $month && $amount > 0) {
        // Check if payment already exists
        $check = $conn->prepare("SELECT id FROM fees WHERE student_id = ? AND month = ?");
        $check->bind_param("is", $student_id, $month);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $msg = "Payment already recorded for this student and month.";
        } else {
            $stmt = $conn->prepare("INSERT INTO fees (student_id, month, amount, status, payment_date) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("isdss", $student_id, $month, $amount, $status, $payment_date);
            $stmt->execute();
            $stmt->close();
            $msg = "Payment recorded successfully.";
        }
        $check->close();
    } else {
        $msg = "Please fill all fields correctly.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Record Payment</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="custom_css/record-payment.css">
</head>
<body>
<div class="form-container">
    <h2>Record Student Payment</h2>

    <?php if (!empty($msg)): ?>
        <div class="alert alert-info"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <form method="POST" class="row g-3">
        <div class="col-md-4">
            <label class="form-label text-white">Student</label>
            <select name="student_id" class="form-control" required>
                <option value="">Select Student</option>
                <?php while ($row = $students_result->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>">
                        <?= htmlspecialchars($row['name']) ?> (Class <?= htmlspecialchars($row['class']) ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label text-white">Month</label>
            <input type="text" name="month" class="form-control" placeholder="e.g., May 2025" required>
        </div>

        <div class="col-md-4">
            <label class="form-label text-white">Amount</label>
            <input type="number" step="0.01" name="amount" class="form-control" required>
        </div>

        <div class="col-md-12">
  <button type="submit" class="btnback-btn">Record Payment</button>
  <a href="dashboard.php" class="btnback">⬅️ Back to Dashboard</a>
</div>

    </form>
</div>
</body>
</html>
