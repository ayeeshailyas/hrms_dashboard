<?php
// insert_notice.php

// Connect to database
$conn = new mysqli("localhost", "root", "", "hrms_dashboard");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect POST data
$status = isset($_POST['status']) ? $_POST['status'] : '';
$title = $_POST['title'];
$short_desc = $_POST['short_desc'];
$long_desc = $_POST['long_desc'];

// Insert query
$sql = "INSERT INTO notices (title, short_desc, long_desc, status, created_at)
        VALUES (?, ?, ?, ?, NOW())";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $title, $short_desc, $long_desc, $status);

if ($stmt->execute()) {
    // Redirect to manage page after successful insert
    header("Location: manage_notice.php?msg=success");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
