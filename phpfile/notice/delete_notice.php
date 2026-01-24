<?php
// delete_notice.php

$conn = new mysqli("localhost", "root", "", "hrms_dashboard");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $deleteQuery = "DELETE FROM notices WHERE id = $id";
    if ($conn->query($deleteQuery)) {
        header("Location: manage_notice.php?msg=deleted");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "Invalid ID!";
}

$conn->close();
?>
