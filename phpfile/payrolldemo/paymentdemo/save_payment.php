<?php
$conn = new mysqli("localhost", "root", "", "hrms_dashboard");

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Process form data
$emp_id = $_POST['employee'];
$payment_month = $_POST['award_month'];
$enrollment_type = $_POST['enrollment_type'];
$fine_deduction = floatval($_POST['fine_deduction']);
$comments = $_POST['comments'];
$gross_salary = floatval($_POST['gross_salary']);
$total_deduction = floatval($_POST['total_deduction']);
$net_salary = floatval($_POST['net_salary']);
$payment_amount = floatval($_POST['payment_amount']);

// Check if payment already exists for this month
$check_sql = "SELECT id FROM salary_payments WHERE emp_id = ? AND payment_month = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("ss", $emp_id, $payment_month);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    // Update existing record
    $update_sql = "UPDATE salary_payments SET 
        payment_date = CURDATE(),
        gross_salary = ?,
        total_deduction = ?,
        net_salary = ?,
        fine_deduction = ?,
        payment_amount = ?,
        enrollment_type = ?,
        comments = ?
        WHERE emp_id = ? AND payment_month = ?";

    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("dddddssss",
        $gross_salary, $total_deduction, $net_salary,
        $fine_deduction, $payment_amount, $enrollment_type,
        $comments, $emp_id, $payment_month);

    if ($update_stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Payment updated successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating payment: ' . $conn->error]);
    }

    $update_stmt->close();
} else {
    // Insert new record
    $insert_sql = "INSERT INTO salary_payments 
        (emp_id, payment_month, payment_date, gross_salary, 
        total_deduction, net_salary, fine_deduction, 
        payment_amount, enrollment_type, comments) 
        VALUES (?, ?, CURDATE(), ?, ?, ?, ?, ?, ?, ?)";

    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("ssdddddss",
        $emp_id, $payment_month, $gross_salary, $total_deduction,
        $net_salary, $fine_deduction, $payment_amount,
        $enrollment_type, $comments);

    if ($insert_stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Payment recorded successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error recording payment: ' . $conn->error]);
    }

    $insert_stmt->close();
}

$check_stmt->close();
$conn->close();
?>