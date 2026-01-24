<?php
$conn = new mysqli("localhost", "root", "", "hrms_dashboard");
$designation = $_GET['designation'];

$sql = "SELECT id, f_name, l_name FROM employee WHERE designation = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $designation);
$stmt->execute();
$result = $stmt->get_result();

$employees = [];
while ($row = $result->fetch_assoc()) {
    $employees[] = $row;
}
echo json_encode($employees);
?>