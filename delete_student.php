<?php
require_once 'session.php';
require_once 'db_connect.php';

$id = $_GET['id'] ?? null;
if ($id) {
    // First delete related fees
    $stmt = $conn->prepare("DELETE FROM fees WHERE student_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // Then delete the student
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: view_students.php?msg=Student+deleted");
exit();
