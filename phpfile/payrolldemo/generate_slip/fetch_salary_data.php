<?php
$conn = new mysqli("localhost", "root", "", "hrms_dashboard");

$emp_id = $_GET['emp_id'] ?? '';
$salary_month = $_GET['salary_month'] ?? ''; // Expecting format YYYY-MM

if (!$emp_id || !$salary_month) {
    exit('<tr><td colspan="7">Missing data</td></tr>');
}

// Extract year and month from input
list($year, $month) = explode('-', $salary_month);

// Check salary_payments table for matching emp_id AND same month/year
$payment_q = $conn->prepare("
    SELECT * FROM salary_payments 
    WHERE emp_id = ? 
    AND YEAR(payment_date) = ?
    AND MONTH(payment_date) = ?
");
$payment_q->bind_param("iii", $emp_id, $year, $month);
$payment_q->execute();
$payment_result = $payment_q->get_result();

$paid = false;
if ($payment_result->num_rows > 0) {
    $paid = true;
    $payment_data = $payment_result->fetch_assoc();
    $net = $payment_data['net_salary'];
    $gross = $payment_data['gross_salary'];
    $deduction = $payment_data['total_deduction'];
} else {
    // Get latest salary details if no payment found
    $salary_q = $conn->prepare("SELECT * FROM salary_details WHERE emp_id = ? ORDER BY created_at DESC LIMIT 1");
    $salary_q->bind_param("i", $emp_id);
    $salary_q->execute();
    $salary_data = $salary_q->get_result()->fetch_assoc();

    $net = $salary_data['net_salary'];
    $gross = $salary_data['gross_salary'];
    $deduction = $salary_data['total_deduction'];
}

// Get employee info
$emp_stmt = $conn->prepare("SELECT id AS emp_id, CONCAT(f_name, ' ', l_name) as name FROM employee WHERE id = ?");
$emp_stmt->bind_param("i", $emp_id);
$emp_stmt->execute();
$emp = $emp_stmt->get_result()->fetch_assoc();

// Custom status badges
$status = $paid
    ? '<span class="text-white px-3 py-1 rounded text-[14px]" style="background: linear-gradient(to right, #0ac282, #0df3a3)">Paid</span>'
    : '<span class="text-white px-3 py-1 rounded text-[14px]" style="background: linear-gradient(to right, #fe5d70, #fe909d)">Unpaid</span>';

// Generate Payslip link - modified to include both emp_id and salary_month
$action = $paid
    ? "<a href='generate.php?emp_id={$emp_id}&salary_month={$salary_month}' class='text-[#0ac282] font-medium'>Generate Payslip</a>"
    : "<a href='make_payment.php?emp_id={$emp_id}' class='text-[#fe5d70] font-medium'>Make Payment</a>";

echo "
<tr class='hover:bg-gray-50'>
    <td class='py-3 px-4'>{$emp['emp_id']}</td>
    <td class='py-3 px-4'>{$emp['name']}</td>
    <td class='py-3 px-4'>{$net}</td>
    <td class='py-3 px-4'>{$gross}</td>
    <td class='py-3 px-4'>{$deduction}</td>
    <td class='py-3 px-4'>{$status}</td>
    <td class='py-3 px-4'>{$action}</td>
</tr>";
?>