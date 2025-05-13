<?php
require_once 'session.php';
require_once 'db_connect.php';

if (!isset($_GET['id'])) {
    echo "Invalid access. No receipt ID provided.";
    exit;
}

$fee_id = intval($_GET['id']);

// Fetch fee + student details
$stmt = $conn->prepare("SELECT f.*, s.name, s.class, s.roll_number, s.guardian_contact 
                        FROM fees f 
                        JOIN students s ON f.student_id = s.id 
                        WHERE f.id = ?");
$stmt->bind_param("i", $fee_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "No receipt found.";
    exit;
}

$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Fee Receipt</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        @media print {
            .no-print { display: none; }
        }
        .receipt-box {
            border: 1px solid #ddd;
            padding: 20px;
            margin-top: 30px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="receipt-box">
        <h3 class="text-center">School Fee Receipt</h3>
        <hr>
        <p><strong>Receipt ID:</strong> <?= $data['id'] ?></p>
        <p><strong>Date:</strong> <?= $data['payment_date'] ?></p>

        <h5>Student Information</h5>
        <p><strong>Name:</strong> <?= htmlspecialchars($data['name']) ?></p>
        <p><strong>Class:</strong> <?= htmlspecialchars($data['class']) ?></p>
        <p><strong>Roll No:</strong> <?= htmlspecialchars($data['roll_number']) ?></p>
        <p><strong>Guardian Contact:</strong> <?= htmlspecialchars($data['guardian_contact']) ?></p>

        <h5>Payment Details</h5>
        <p><strong>Month:</strong> <?= htmlspecialchars($data['month']) ?></p>
        <p><strong>Amount Paid:</strong> PKR <?= number_format($data['amount'], 2) ?></p>
        <p><strong>Status:</strong> <span class="badge bg-success"><?= ucfirst($data['status']) ?></span></p>

        <hr>
        <p class="text-end">Authorized Signature</p>
    </div>

    <div class="text-center mt-4 no-print">
        <button class="btn btn-primary" onclick="window.print()">Print Receipt</button>
        <a href="view_payments.php" class="btn btn-secondary">Back</a>
    </div>
</div>
</body>
</html>
