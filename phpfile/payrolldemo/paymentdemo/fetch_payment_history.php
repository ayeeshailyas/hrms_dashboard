<?php
$conn = new mysqli("localhost", "root", "", "hrms_dashboard");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} // Your database connection file

header('Content-Type: application/json');

if (!isset($_GET['emp_id'])) {
    echo json_encode(['success' => false, 'message' => 'Employee ID required']);
    exit;
}

$emp_id = $_GET['emp_id'];
$query = $conn->query("SELECT * FROM salary_payments WHERE emp_id = '$emp_id' ORDER BY payment_month DESC");
$payments = [];

while ($row = $query->fetch_assoc()) {
    $payments[] = $row;
}

echo json_encode(['success' => true, 'data' => $payments]);
?>