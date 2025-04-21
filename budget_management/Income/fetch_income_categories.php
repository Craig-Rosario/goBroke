<?php
session_start();
include("../Registration/database.php");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT income_category, SUM(income_amount) AS total_amount FROM incomes WHERE user_id = ? GROUP BY income_category");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$categories = [];
$amounts = [];

while ($row = $result->fetch_assoc()) {
    $categories[] = $row['income_category'];
    $amounts[] = floatval($row['total_amount']);
}

$stmt->close();
$conn->close();

echo json_encode(['labels' => $categories, 'amounts' => $amounts]);
?>