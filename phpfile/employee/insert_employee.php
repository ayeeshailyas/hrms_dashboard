<?php

$conn = new mysqli("localhost", "root", "", "hrms_dashboard");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
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
//    $designation = $_POST['designation'];
    $combined = $_POST['designation_combined'];
    list($department, $designation) = explode('||', $combined);


    $join_date = $_POST['join_date'];

    // Dummy file names — you can enhance this later
    $photo = $_FILES['photo']['name'];
    $resume = $_FILES['resume']['name'];
    $offer_letter = $_FILES['offer_letter']['name'];
    $joining_letter = $_FILES['joining_letter']['name'];
    $contract_paper = $_FILES['contract_paper']['name'];
    $id_proof = $_FILES['id_proof']['name'];
    $other_document = $_FILES['other_document']['name'];

    // File upload logic (simple, assumes 'uploads/' folder exists)
    move_uploaded_file($_FILES['photo']['tmp_name'], "uploads/$photo");
    move_uploaded_file($_FILES['resume']['tmp_name'], "uploads/$resume");
    move_uploaded_file($_FILES['offer_letter']['tmp_name'], "uploads/$offer_letter");
    move_uploaded_file($_FILES['joining_letter']['tmp_name'], "uploads/$joining_letter");
    move_uploaded_file($_FILES['contract_paper']['tmp_name'], "uploads/$contract_paper");
    move_uploaded_file($_FILES['id_proof']['tmp_name'], "uploads/$id_proof");
    move_uploaded_file($_FILES['other_document']['tmp_name'], "uploads/$other_document");

    $sql = "INSERT INTO employee (
        emp_id, f_name, l_name, dob, gender, marital_status, father_name, nationality, passport_no,
        photo, bank_name, branch_name, account_name, account_number, address, city, contact_nationality,
        mobile, phone, email, resume, offer_letter, joining_letter, contract_paper, id_proof, other_document,
        designation,department, join_date
    ) VALUES (
        '$emp_id', '$f_name', '$l_name', '$dob', '$gender', '$marital_status', '$father_name', '$nationality', '$passport_no',
        '$photo', '$bank_name', '$branch_name', '$account_name', '$account_number', '$address', '$city', '$contact_nationality',
        '$mobile', '$phone', '$email', '$resume', '$offer_letter', '$joining_letter', '$contract_paper', '$id_proof', '$other_document',
        '$designation','$department', '$join_date'
    )";

    if ($conn->query($sql) === TRUE) {
        header("Location: list_employee.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
