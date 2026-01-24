<?php
$conn = new mysqli("localhost", "root", "", "hrms_dashboard");

$id = $_GET['id'] ?? null;

if ($id) {
    // Find the holiday to get the month before deletion
    $res = $conn->query("SELECT start_date FROM holidays WHERE id = $id");
    $row = $res->fetch_assoc();
    $month = $row ? date('F', strtotime($row['start_date'])) : date('F');

    $conn->query("DELETE FROM holidays WHERE id = $id");
    header("Location: index.php?month=$month&status=deleted");

} else {
    die("Invalid ID.");
}
?>
