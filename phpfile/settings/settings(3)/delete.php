<?php
$conn = new mysqli("localhost", "root", "", "hrms_dashboard");

$id = $_GET['id'] ?? null;

if ($id) {
    // Find the holiday to get the month before deletion
    $stmt = $conn->prepare("SELECT start_date FROM holidays WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $month = $row ? date('F', strtotime($row['start_date'])) : date('F');

    $del_stmt = $conn->prepare("DELETE FROM holidays WHERE id = ?");
    $del_stmt->bind_param("i", $id);
    $del_stmt->execute();
    header("Location: index.php?month=$month&status=deleted");

} else {
    die("Invalid ID.");
}
?>
