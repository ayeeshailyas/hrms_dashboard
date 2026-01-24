<?php
// update_notice.php

$conn = new mysqli("localhost", "root", "", "hrms_dashboard");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect data from POST
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$status = isset($_POST['status']) ? $_POST['status'] : '';
$title = $_POST['title'];
$short_desc = $_POST['short_desc'];
$long_desc = $_POST['long_desc'];

// Update query
$sql = "UPDATE notices 
        SET title = ?, short_desc = ?, long_desc = ?, status = ? 
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $title, $short_desc, $long_desc, $status, $id);

if ($stmt->execute()) {
    header("Location: manage_notice.php?msg=updated");
    exit();
} else {
    echo "Error updating notice: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
