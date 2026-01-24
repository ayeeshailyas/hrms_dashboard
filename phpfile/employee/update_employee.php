<?php
$conn = new mysqli("localhost", "root", "", "hrms_dashboard");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'];
    $emp_id = $_POST['emp_id'];
    $f_name = $_POST['f_name'];
    $l_name = $_POST['l_name'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $marital_status = $_POST['marital_status'];
    $father_name = $_POST['father_name'];
    $nationality = $_POST['nationality'];
    $passport_no = $_POST['pass_no'];
    $bank_name = $_POST['bank_name'];
    $branch_name = $_POST['branch_name'];
    $account_name = $_POST['account_name'];
    $account_number = $_POST['account_number'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $contact_nationality = $_POST['contact_nationality'];
    $mobile = $_POST['mobile'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $combined = $_POST['designation_combined'];
    list($department, $designation) = explode('||', $combined);
    $join_date = $_POST['join_date'];

    // File handling
    function handleFileUpdate($fieldName, $existingFile) {
        if (!empty($_FILES[$fieldName]['name'])) {
            $newFile = $_FILES[$fieldName]['name'];
            move_uploaded_file($_FILES[$fieldName]['tmp_name'], "uploads/$newFile");
            return $newFile;
        }
        return $existingFile;
    }

    // Fetch existing file names
    $result = $conn->query("SELECT * FROM employee WHERE id = $id");
    if ($result->num_rows === 0) die("Employee not found.");
    $row = $result->fetch_assoc();

    $photo = handleFileUpdate('photo', $row['photo']);
    $resume = handleFileUpdate('resume', $row['resume']);
    $offer_letter = handleFileUpdate('offer_letter', $row['offer_letter']);
    $joining_letter = handleFileUpdate('joining_letter', $row['joining_letter']);
    $contract_paper = handleFileUpdate('contract_paper', $row['contract_paper']);
    $id_proof = handleFileUpdate('id_proof', $row['id_proof']);
    $other_document = handleFileUpdate('other_document', $row['other_document']);

    $sql = "UPDATE employee SET
        emp_id='$emp_id', f_name='$f_name', l_name='$l_name', dob='$dob', gender='$gender',
        marital_status='$marital_status', father_name='$father_name', nationality='$nationality',
        passport_no='$passport_no', photo='$photo', bank_name='$bank_name', branch_name='$branch_name',
        account_name='$account_name', account_number='$account_number', address='$address',
        city='$city', contact_nationality='$contact_nationality', mobile='$mobile', phone='$phone',
        email='$email', resume='$resume', offer_letter='$offer_letter', joining_letter='$joining_letter',
        contract_paper='$contract_paper', id_proof='$id_proof', other_document='$other_document',
        designation='$designation', department='$department', join_date='$join_date'
        WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: list_employee.php?updated=1");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
