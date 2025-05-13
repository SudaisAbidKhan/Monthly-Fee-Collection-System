<?php
require_once 'session.php';
require_once 'db_connect.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $class = trim($_POST['class']);
    $monthly_fee = floatval($_POST['monthly_fee']);

    if ($class && $monthly_fee > 0) {
        // Insert or update fee for the class
        $stmt = $conn->prepare("INSERT INTO class_fees (class, monthly_fee) VALUES (?, ?) ON DUPLICATE KEY UPDATE monthly_fee = VALUES(monthly_fee)");
        $stmt->bind_param("sd", $class, $monthly_fee);
        $stmt->execute();
        $stmt->close();

        $msg = "Fee updated for class $class.";
    } else {
        $msg = "Please enter valid class and fee.";
    }
}

// Fetch all class fee structures
$fees_result = $conn->query("SELECT * FROM class_fees ORDER BY class ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Fee Structure</title>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="custom_css/fee-structure.css">
</head>
<body>
<div class="form-container">
  <h2>Define Monthly Fee per Class</h2>

  <?php if (!empty($msg)): ?>
    <div class="alert alert-info"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>

 <form method="POST" class="glass-card mb-5">
  <h5 class="form-heading">Add Class & Fee</h5>
  <div class="mb-3">
    <label class="form-label">Class</label>
    <input type="text" name="class" class="form-control" placeholder="Class (e.g., Grade 1)" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Monthly Fee</label>
    <input type="number" step="0.01" name="monthly_fee" class="form-control" placeholder="Monthly Fee" required>
  </div>
  <button type="submit" class="btn btn-outline-light">
    💾 Save
  </button>
</form>


  <h4 class="text-white">Current Fee Structure</h4>
  <table class="table table-dark table-bordered table-striped mt-3">
    <thead>
      <tr>
        <th>Class</th>
        <th>Monthly Fee</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $fees_result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['class']) ?></td>
          <td>PKR <?= number_format($row['monthly_fee'], 2) ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <a href="dashboard.php" class="btnback">⬅️ Back to Dashboard</a>
</div>
</body>
</html>
