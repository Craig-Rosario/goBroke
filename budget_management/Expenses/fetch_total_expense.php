<?php
session_start();
include("../Registration/database.php");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['total' => 0]);
    exit();
}

$user_id = $_SESSION['user_id'];

$totalExpense = $conn->query("SELECT SUM(expense_amount) AS total FROM expenses WHERE user_id = $user_id")->fetch_assoc()['total'] ?? 0;

echo json_encode(['total' => $totalExpense]);

$conn->close();
?>