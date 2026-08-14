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
$stmt = $conn->prepare("SELECT * FROM salary_payments WHERE emp_id = ? ORDER BY payment_month DESC");
$stmt->bind_param("i", $emp_id);
$stmt->execute();
$query = $stmt->get_result();
$payments = [];

while ($row = $query->fetch_assoc()) {
    $payments[] = $row;
}

echo json_encode(['success' => true, 'data' => $payments]);
?>