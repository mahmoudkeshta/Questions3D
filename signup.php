<?php 

include './connect.php';

$username = filterRequest('name');
$email = filterRequest('email');
$subject = filterRequest('subject');
$oracle_code = filterRequest('oracle_code');



$stmt = $con->prepare("SELECT * FROM teachers WHERE email = ? OR oracle_code = ?");
$stmt->execute(array($email, $oracle_code));
$count = $stmt->rowCount();

if ($count > 0) {
    printFailure("oracle code or email");
} else {
    $data = array(
        "name" => $username,
        "email" => $email,
        "oracle_code" => $oracle_code,
        "subject" => $subject,
    );
  
    insertData("teachers", $data);
}