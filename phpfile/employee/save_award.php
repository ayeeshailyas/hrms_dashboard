<?php
$conn = new mysqli("localhost", "root", "", "hrms_dashboard");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = $_POST['employee_id'];
    $award_name = $_POST['award_name'];
    $gift_item = $_POST['gift_item'];
    $award_amount = $_POST['award_amount'];
    $award_month = $_POST['award_month'];

    $stmt = $conn->prepare("INSERT INTO employee_awards (employee_id, award_name, gift_item, award_amount, award_month) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issds", $employee_id, $award_name, $gift_item, $award_amount, $award_month);
    $stmt->execute();
    header("Location: ".$_SERVER['HTTP_REFERER']);
}
?>