<?php
$id = $_GET['id'];
$conn = new mysqli("localhost", "root", "", "hrms_dashboard");
$stmt = $conn->prepare("DELETE FROM employee WHERE id = ?");
$stmt->bind_param("i", $id);
if ($stmt->execute() === TRUE) {
    header("Location: list_employee.php?deleted=1");
} else {
    echo "Error deleting: " . $conn->error;
}
?>
