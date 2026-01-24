<?php
$id = $_GET['id'];
$conn = new mysqli("localhost", "root", "", "hrms_dashboard");
if ($conn->query("DELETE FROM employee WHERE id = $id") === TRUE) {
    header("Location: list_employee.php?deleted=1");
} else {
    echo "Error deleting: " . $conn->error;
}
?>
