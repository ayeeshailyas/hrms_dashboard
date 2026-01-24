<?php
$conn = new mysqli("localhost", "root", "", "hrms_dashboard");

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

$emp_id = $_GET['emp_id'];
$payment_month = $_GET['payment_month'];

$sql = "SELECT * FROM salary_payments WHERE emp_id = ? AND payment_month = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $emp_id, $payment_month);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    echo json_encode(['success' => true, 'exists' => true, 'data' => $data]);
} else {
    echo json_encode(['success' => true, 'exists' => false]);
}

$stmt->close();
$conn->close();
?>