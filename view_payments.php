<?php
require_once 'session.php';
require_once 'db_connect.php';

// Fetch students for dropdown
$students = $conn->query("SELECT id, name, class FROM students ORDER BY name");

// Process filters
$filter_student = $_GET['student_id'] ?? '';
$filter_month = $_GET['month'] ?? '';

$sql = "SELECT f.*, s.name AS student_name, s.class 
        FROM fees f 
        JOIN students s ON f.student_id = s.id 
        WHERE 1";

$params = [];
$types = "";

// Add filters
if ($filter_student !== '') {
    $sql .= " AND f.student_id = ?";
    $types .= "i";
    $params[] = $filter_student;
}
if ($filter_month !== '') {
    $sql .= " AND f.month = ?";
    $types .= "s";
    $params[] = $filter_month;
}
$sql .= " ORDER BY f.payment_date DESC";

$stmt = $conn->prepare($sql);
if ($types) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Payments</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="custom_css/view-payments.css">
</head>
<body>
<div class="payments-container">
    <h2>Payment History</h2>

    <form method="GET" class="row g-3 mb-4 filter-form">
  <div class="col-md-4">
    <label class="form-label text-white ">Filter by Student</label>
    <select name="student_id" class="form-control student">
      <option value="">All Students</option>
      <?php while ($s = $students->fetch_assoc()): ?>
        <option value="<?= $s['id'] ?>" <?= ($filter_student == $s['id']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($s['name']) ?> (Class <?= $s['class'] ?>)
        </option>
      <?php endwhile; ?>
    </select>
  </div>

  <div class="col-md-4">
    <label class="form-label text-white">Filter by Month</label>
    <input type="text" name="month" class="form-control" value="<?= htmlspecialchars($filter_month) ?>" placeholder="e.g., May 2025">
  </div>

  <div class="col-md-4 d-flex align-items-end">
    <button type="submit" class="btn-glass me-2">Apply Filters</button>
    <a href="view_payments.php" class="btn-glass">Reset</a>
  </div>
</form>


    <div class="table-responsive">
        <table class="table table-bordered table-striped table-dark table-hover rounded-3 overflow-hidden">
            <thead class="table-light">
                <tr>
                    <th>Student</th>
                    <th>Class</th>
                    <th>Month</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Payment Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['student_name']) ?></td>
                            <td><?= htmlspecialchars($row['class']) ?></td>
                            <td><?= htmlspecialchars($row['month']) ?></td>
                            <td>PKR <?= number_format($row['amount'], 2) ?></td>
                            <td>
                                <span class="badge bg-<?= $row['status'] === 'paid' ? 'success' : 'danger' ?>">
                                    <?= ucfirst($row['status']) ?>
                                </span>
                            </td>
                            <td>
                                <?= htmlspecialchars($row['payment_date']) ?>
                                <?php if ($row['status'] === 'paid'): ?>
                                    <br>
                                    <a href="generate_receipt.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-light mt-1">Generate Receipt</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center">No records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <a href="dashboard.php" class="btnback">⬅️ Back to Dashboard</a>
</div>
</body>
</html>

