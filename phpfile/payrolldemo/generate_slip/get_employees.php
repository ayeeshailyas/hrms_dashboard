<?php
$conn = new mysqli("localhost", "root", "", "hrms_dashboard");

if ($conn->connect_error) {
    die('<option value="">Database error</option>');
}

$designation = $_GET['designation'] ?? '';
if (empty($designation)) {
    die('<option value="">Select designation first</option>');
}

$parts = explode("||", $designation);
if (count($parts) !== 2) {
    die('<option value="">Invalid designation</option>');
}

$department = $parts[0];
$designation = $parts[1];

$stmt = $conn->prepare("SELECT id, CONCAT(f_name, ' ', l_name) AS name
                        FROM employee
                        WHERE department=? AND designation=?
                        ORDER BY f_name, l_name");
$stmt->bind_param("ss", $department, $designation);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows === 0) {
    die('<option value="">No employees found</option>');
}

$options = '';
while ($row = $result->fetch_assoc()) {
    $options .= sprintf('<option value="%d">%s</option>', $row['id'], htmlspecialchars($row['name']));
}

echo $options;
$conn->close();
?>
