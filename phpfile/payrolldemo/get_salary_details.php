<?php
$conn = new mysqli("localhost", "root", "", "hrms_dashboard");
if ($conn->connect_error) {
    die("Connection failed");
}

$emp_id = $_GET['emp_id'];
$stmt = $conn->prepare("SELECT * FROM salary_details WHERE emp_id = ?");
$stmt->bind_param("i", $emp_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    echo json_encode($res->fetch_assoc());
} else {
    echo json_encode(null);
}
?>
