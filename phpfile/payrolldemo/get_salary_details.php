<?php
$conn = new mysqli("localhost", "root", "", "hrms_dashboard");
if ($conn->connect_error) {
    die("Connection failed");
}

$emp_id = $_GET['emp_id'];
$res = $conn->query("SELECT * FROM salary_details WHERE emp_id = $emp_id");

if ($res->num_rows > 0) {
    echo json_encode($res->fetch_assoc());
} else {
    echo json_encode(null);
}
?>
