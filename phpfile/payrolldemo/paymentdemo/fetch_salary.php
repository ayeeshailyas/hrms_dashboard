<?php
$conn = new mysqli("localhost", "root", "", "hrms_dashboard");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


header('Content-Type: application/json');

if (!isset($_GET['emp_id'])) {
    echo json_encode(['success' => false, 'message' => 'Employee ID required']);
    exit;
}

$emp_id = $_GET['emp_id'];
$stmt = $conn->prepare("SELECT * FROM salary_details WHERE emp_id = ? LIMIT 1");
$stmt->bind_param("i", $emp_id);
$stmt->execute();
$query = $stmt->get_result();

if ($query->num_rows > 0) {
    $data = $query->fetch_assoc();
    echo json_encode(['success' => true, 'data' => $data]);
} else {
    echo json_encode(['success' => false, 'message' => 'Salary details not found for this employee']);
}
?>