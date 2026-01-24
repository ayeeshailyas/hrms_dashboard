<?php
$conn = new mysqli("localhost", "root", "", "hrms_dashboard");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $award_id = $_GET['id'];
    $sql = "DELETE FROM employee_awards WHERE id = $award_id";
    if ($conn->query($sql) === TRUE) {
        header("Location: employee_award.php");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>