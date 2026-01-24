<?php
require_once 'db.php';

$item_name = $_POST['item_name'];
$purchased_from = $_POST['purchased_from'];
$purchase_date = $_POST['purchase_date'];
$amount_spent = $_POST['amount_spent'];
$employee_id = $_POST['employee_id'];

$bill_copy = '';
if (isset($_FILES['bill_copy']) && $_FILES['bill_copy']['error'] == 0) {
    $upload_dir = 'uploads/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    $filename = time() . '_' . basename($_FILES['bill_copy']['name']);
    $target = $upload_dir . $filename;
    if (move_uploaded_file($_FILES['bill_copy']['tmp_name'], $target)) {
        $bill_copy = $filename;
    }
}
// Add this validation before processing
$purchase_date = $_POST['purchase_date'];
if (!strtotime($purchase_date)) {
    die("Invalid date format");
}

// Format the date properly before inserting
$purchase_date = date('Y-m-d', strtotime($purchase_date));

$sql = "INSERT INTO expenses (item_name, purchased_from, purchase_date, amount_spent, employee_id, bill_copy)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssdis", $item_name, $purchased_from, $purchase_date, $amount_spent, $employee_id, $bill_copy);

if ($stmt->execute()) {
    header("Location: expense_report.php?status=added");

    exit;
} else {
    echo "Error: " . $stmt->error;
}
?>
<?php
