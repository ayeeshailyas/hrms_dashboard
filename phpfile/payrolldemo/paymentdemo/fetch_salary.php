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
$query = $conn->query("SELECT * FROM salary_details WHERE emp_id = '$emp_id' LIMIT 1");

if ($query->num_rows > 0) {
    $data = $query->fetch_assoc();
    echo json_encode(['success' => true, 'data' => $data]);
} else {
    echo json_encode(['success' => false, 'message' => 'Salary details not found for this employee']);
}
?>