<?php
include_once 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("DELETE FROM events WHERE id=$id");
}

header("Location: index.php");
?>
