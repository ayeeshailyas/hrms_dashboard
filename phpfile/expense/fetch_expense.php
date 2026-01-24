<?php
require_once 'db.php';

$month = $_GET['month'];
$monthNumber = date('m', strtotime($month));

$sql = "SELECT e.*, emp.f_name, emp.l_name 
        FROM expenses e 
        JOIN employee emp ON emp.id = e.employee_id 
        WHERE MONTH(e.purchase_date) = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $monthNumber);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;
?>


<table class="w-full text-sm border text-left">
    <thead>
    <tr>
        <th class="px-2 py-2 font-medium border">Sr.</th>
        <th class="px-4 py-2 font-medium border">Item Name</th>
        <th class="px-4 py-2 font-medium border">Purchased From</th>
        <th class="px-4 py-2 font-medium border">Purchase Date</th>
        <th class="px-4 py-2 font-medium border">Employee Name</th>
        <th class="px-4 py-2 font-medium border">Bill Copy</th>
        <th class="px-4 py-2 font-medium border">Amount</th>
    </tr>
    </thead>
    <tbody >
    <?php
    $sr = 1;
    while ($row = $result->fetch_assoc()):
        $total += $row['amount_spent'];
        ?>
        <tr>
            <td class="px-2 py-2 border"><?= $sr++ ?></td>
            <td class="px-3 py-2 border"><?= htmlspecialchars($row['item_name']) ?></td>
            <td class="px-3 py-2 border"><?= htmlspecialchars($row['purchased_from']) ?></td>
            <td class="px-3 py-2 border"><?= date('d M, Y', strtotime($row['purchase_date'])) ?></td>
            <td class="px-3 py-2 border"><?= htmlspecialchars($row['f_name'] . ' ' . $row['l_name']) ?>
            </td>
            <td class="px-3 py-2 border">
                <?php if ($row['bill_copy']): ?>
                    <a href="uploads/<?= $row['bill_copy'] ?>" target="_blank">View</a>
                <?php else: ?>
                    No File
                <?php endif; ?>
            </td>
            <td class="px-3 py-2 border"><?= number_format($row['amount_spent'], 2) ?></td>
        </tr>
    <?php endwhile; ?>

    <?php if ($sr === 1): ?>
        <tr>
            <td colspan="7" class="text-center py-6 text-gray-500">No expenses found for this month.</td>
        </tr>
    <?php else: ?>
        <tr class="bg-gray-100 font-bold">
            <td colspan="6" class="text-right px-3 py-2 border">Total Amount</td>
            <td class="px-3 py-2 border"><?= number_format($total, 2) ?></td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
