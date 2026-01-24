<?php
$conn = new mysqli("localhost", "root", "", "hrms_dashboard");

$month = isset($_POST['month']) ? $_POST['month'] : 'July';
$monthNumber = date('m', strtotime($month));

$query = "SELECT * FROM holidays WHERE MONTH(start_date) = '$monthNumber'";
$result = $conn->query($query);
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-md font-medium text-gray-800"><i class="fa fa-calendar-days mr-2"></i><?= $month ?></h1>
    <a href="add_holiday.php" class="bg-[#01a9ac] text-white text-[12px] px-4 py-2 rounded hover:bg-[#07e4e8] transition-all ease-in-out duration-500"> Add Holiday</a>
</div>

<div class="overflow-x-auto">
    <table class="w-full text-sm border text-left">
        <thead>
        <tr>
            <th class="px-2 py-2 border">Sr.</th>
            <th class="px-3 py-3 border">Event Name</th>
            <th class="px-3 py-3 border">Start Date</th>
            <th class="px-3 py-3 border">End Date</th>
            <th class="px-3 py-3 border text-center">Action</th>
        </tr>
        </thead>
        <tbody class="hover:bg-gray-200">
        <?php
        $sr = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<tr >
                    <td class='px-2 py-2 border'>$sr</td>
                    <td class='px-3 py-4 border'>{$row['event_name']}</td>
                    <td class='px-3 py-4 border'>" . date('d M, Y', strtotime($row['start_date'])) . "</td>
                    <td class='px-3 py-4 border'>" . date('d M, Y', strtotime($row['end_date'])) . "</td>
                  <td class='px-3 py-4 border text-center'>
                        <div class='flex justify-center items-center gap-0'>
                            <a href='edit_holiday.php?id={$row['id']}'
                                 class='inline-flex items-center bg-[#01a9ac] text-white px-3 py-2 text-xs hover:bg-[#07e4e8] transition-all duration-300'>
                                  <i class='fa fa-edit mr-1'></i> Edit
                             </a>
                             <a href='delete.php?id={$row['id']}'
                                onclick=\"return confirm('Are you sure?')\"
                                class='inline-flex items-center bg-[#fe5d70] text-white px-3 py-2 text-xs hover:bg-[#fe5d70] transition-all duration-300'>
                                <i class='fa fa-trash mr-1'></i> Delete
                            </a>
                        </div>
                </td>
                </tr>";
            $sr++;
        }

        if ($sr === 1) {
            echo "<tr><td colspan='5' class='text-center py-6 text-gray-500'>No holidays found for this month.</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>
