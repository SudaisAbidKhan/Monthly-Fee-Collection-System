<?php
require_once 'session.php';
require_once 'db_connect.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $class = trim($_POST['class']);
    $roll_number = trim($_POST['roll_number']);
    $guardian_contact = trim($_POST['guardian_contact']);

    if ($name && $class && $roll_number && $guardian_contact) {
        // Check for duplicate roll number
        // Check for duplicate roll number within the same class
$check = $conn->prepare("SELECT id FROM students WHERE roll_number = ? AND class = ?");
$check->bind_param("ss", $roll_number, $class);

        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "❌ A student with this roll number already exists.";
        } else {
            $stmt = $conn->prepare("INSERT INTO students (name, class, roll_number, guardian_contact, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->bind_param("ssss", $name, $class, $roll_number, $guardian_contact);
            $stmt->execute();
            $stmt->close();
            header("Location: view_students.php?msg=Student+added+successfully");
            exit();
        }

        $check->close();
    } else {
        $error = "❌ All fields are required.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Student</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="custom_css/add-student.css"> <!-- Linked your new style -->
</head>
<body>
<div class="view-container">
    <h2>➕ Add New Student</h2>

    <?php if ($error): ?>
        <div class="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Class</label>
            <input type="text" name="class" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Roll Number</label>
            <input type="text" name="roll_number" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="guardian_contact" class="form-label">Guardian Contact (03XXXXXXXXX)</label>
            <input type="text" id="guardian_contact" name="guardian_contact" class="form-control" maxlength="11" required>
            <div id="contactError" class="text-danger mt-1" style="display: none;">❌ Invalid format. Use 03XXXXXXXXX (11 digits).</div>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn">✅ Add Student</button>
            <a href="dashboard.php" class="btn">⬅️ Back to Dashboard</a>
        </div>
    </form>
</div>

<script src="scripts.js"></script>
</body>
</html>
