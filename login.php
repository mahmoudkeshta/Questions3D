
<?php
include './connect.php';
session_start(); // بدء الجلسة لتخزين بيانات المستخدم

// Ensure the request is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
     // الحصول على المدخلات وتنقيتها
    $email = filterRequest('email');
    $oracle_code = filterRequest('oracle_code');
    // Prepare SQL statement
     $stmt = $con->prepare("SELECT * FROM teachers WHERE email = ? AND oracle_code = ?");
    $stmt->execute([$email, $oracle_code]);

    // Fetch user data
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $count = $stmt->rowCount();

    // Check if user exists and return the result
    if ($count > 0) {
        // Store user ID in session
        $_SESSION['user_id'] = $data['id']; // Assume 'id' is the column name for the user's ID in the Drivers table
       // $_SESSION['license_plate_id'] = $data['license_plate']; 
       // $_SESSION['id_vehicles_id'] = $data['id_vehicles']; 
        // Redirect to home page
        header('Location: ./home.php');
        exit();
    } else {
        echo json_encode(["status" => "failure"]);
    }
}
?>

