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
    $fetch_stmt = $conn->prepare("SELECT * FROM employee WHERE id = ?");
    $fetch_stmt->bind_param("i", $id);
    $fetch_stmt->execute();
    $result = $fetch_stmt->get_result();
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
        emp_id=?, f_name=?, l_name=?, dob=?, gender=?,
        marital_status=?, father_name=?, nationality=?,
        passport_no=?, photo=?, bank_name=?, branch_name=?,
        account_name=?, account_number=?, address=?,
        city=?, contact_nationality=?, mobile=?, phone=?,
        email=?, resume=?, offer_letter=?, joining_letter=?,
        contract_paper=?, id_proof=?, other_document=?,
        designation=?, department=?, join_date=?
        WHERE id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssssssssssssssssssssssssssi",
        $emp_id, $f_name, $l_name, $dob, $gender,
        $marital_status, $father_name, $nationality,
        $passport_no, $photo, $bank_name, $branch_name,
        $account_name, $account_number, $address,
        $city, $contact_nationality, $mobile, $phone,
        $email, $resume, $offer_letter, $joining_letter,
        $contract_paper, $id_proof, $other_document,
        $designation, $department, $join_date,
        $id
    );

    if ($stmt->execute() === TRUE) {
        header("Location: list_employee.php?updated=1");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
