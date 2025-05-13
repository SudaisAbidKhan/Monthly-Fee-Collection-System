<?php
require_once 'session.php';
require_once 'db_connect.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: view_students.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $class = $_POST['class'];
    $roll_number = $_POST['roll_number'];
    $guardian_contact = $_POST['guardian_contact'];

    $stmt = $conn->prepare("UPDATE students SET name=?, class=?, roll_number=?, guardian_contact=? WHERE id=?");
    $stmt->bind_param("ssssi", $name, $class, $roll_number, $guardian_contact, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: view_students.php?msg=Student+updated");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM students WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Student</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Student</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($student['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Class:</label>
            <input type="text" name="class" class="form-control" value="<?= htmlspecialchars($student['class']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Roll Number:</label>
            <input type="text" name="roll_number" class="form-control" value="<?= htmlspecialchars($student['roll_number']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Guardian Contact:</label>
            <input type="text" name="guardian_contact" class="form-control" value="<?= htmlspecialchars($student['guardian_contact']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Student</button>
        <a href="view_students.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>
