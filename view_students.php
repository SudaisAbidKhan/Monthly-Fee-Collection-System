<?php
require_once 'session.php';
require_once 'db_connect.php';

$result = $conn->query("SELECT * FROM students ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Students</title>
    <link rel="stylesheet" href="custom_css/view-students.css">
</head>
<body>
<div class="view-container">
    <h2>Student List</h2>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>

    <div style="margin-bottom: 20px;">
        <a href="add_student.php" class="btn">➕ Add New Student</a>
        <a href="dashboard.php" class="btn">⬅️ Back to Dashboard</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Class</th>
                <th>Roll Number</th>
                <th>Guardian Contact</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['class']) ?></td>
                    <td><?= htmlspecialchars($row['roll_number']) ?></td>
                    <td><?= htmlspecialchars($row['guardian_contact']) ?></td>
                    <td>
                        <a href="edit_student.php?id=<?= $row['id'] ?>" class="btn">✏️ Edit</a>
                        <a href="delete_student.php?id=<?= $row['id'] ?>" class="btn" onclick="return confirm('Delete this student?');">🗑️ Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
