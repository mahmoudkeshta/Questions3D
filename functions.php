<?php

define("MB", 1048576);

function filterRequest($requestname)
{
  return  htmlspecialchars(strip_tags($_POST[$requestname]));
}

function getAllData($table, $where = null, $values = null,$json= true)
{
    global $con;
    $data = array();
    if($where == null){
        $stmt = $con->prepare("SELECT  * FROM $table ");
    }else{
        $stmt = $con->prepare("SELECT  * FROM $table WHERE   $where ");
    }

    $stmt->execute($values);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count  = $stmt->rowCount();
  if($json==true){
    if ($count > 0){
        echo json_encode(array("status" => "success", "data" => $data));
    } else {
        echo json_encode(array("status" => "failure"));
    }
    return $count;
  }else{
    if($count>0){
        return $data;
    }else{
        return          json_encode(array("status" => "failure"));
    }


  }
}

function getAllDataa($table, $where = null, $values = null,$json= true)
{
    global $con;
    $data = array();
    if($where == null){
        $stmt = $con->prepare("SELECT  * FROM $table ");
    }else{
        $stmt = $con->prepare("SELECT  * FROM $table WHERE   $where ");
    }

    $stmt->execute($values);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count  = $stmt->rowCount();
  if($json==true){
    if ($count > 0){
        echo json_encode(array("status" => "success", "data1" => $data));
    } else {
        echo json_encode(array("status" => "failure"));
    }
    return $count;
  }else{
    if($count>0){
        return $data;
    }else{
        return          json_encode(array("status" => "failure"));
    }


  }
}

function insertData($table, $data, $json = true)
{
    global $con;
    foreach ($data as $field => $v)
        $ins[] = ':' . $field;
    $ins = implode(',', $ins);
    $fields = implode(',', array_keys($data));
    $sql = "INSERT INTO $table ($fields) VALUES ($ins)";

    $stmt = $con->prepare($sql);
    foreach ($data as $f => $v) {
        $stmt->bindValue(':' . $f, $v);
    }
    $stmt->execute();
    $count = $stmt->rowCount();
    if ($json == true) {
    if ($count > 0) {
        echo json_encode(array("status" => "success"));
    } else {
        echo json_encode(array("status" => "failure"));
    }
  }
    return $count;
} 


function updateData($table, $data, $where, $json = true)
{
    global $con;
    $cols = array();
    $vals = array();

    foreach ($data as $key => $val) {
        $vals[] = "$val";
        $cols[] = "`$key` =  ? ";
    }
    $sql = "UPDATE $table SET " . implode(', ', $cols) . " WHERE $where";

    $stmt = $con->prepare($sql);
    $stmt->execute($vals);
    $count = $stmt->rowCount();
    if ($json == true) {
    if ($count > 0) {
        echo json_encode(array("status" => "success"));
    } else {
        echo json_encode(array("status" => "failure"));
    }
    }
    return $count;
}


function updateData1($table, $data, $where, $json = true)
{
    global $con;
    $cols = array();
    $vals = array();

    foreach ($data as $key => $val) {
        $vals[] = "$val";
        $cols[] = "`$key` =  ? ";
    }
    $sql = "UPDATE $table SET " . implode(', ', $cols) . " WHERE $where";

    $stmt = $con->prepare($sql);
    $stmt->execute($vals);
    $count = $stmt->rowCount();
  
    return $count;
}

function deleteData($table, $where, $json = true)
{
    global $con;
    $stmt = $con->prepare("DELETE FROM $table WHERE $where");
    $stmt->execute();
    $count = $stmt->rowCount();
    if ($json == true) {
        if ($count > 0) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }
    return $count;
}



function getData($table, $where = null, $values = null,$json= true)
{
    global $con;
    $data = array();
    $stmt = $con->prepare("SELECT  * FROM $table WHERE   $where ");
    $stmt->execute($values);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $count  = $stmt->rowCount();
    if ($json == true) {
    if ($count > 0){
        echo json_encode(array("status" => "success", "data" => $data));
    } else {
        echo json_encode(array("status" => "failure"));
    }}else{
        return $count;
    }
    return $count;
}
function imageUpload($dir, $imageRequest)
{
    global $msgError;
   if (isset($_FILES[$imageRequest])) {
        $imagename  = rand(1000, 10000) . $_FILES[$imageRequest]['name'];
        $imagetmp   = $_FILES[$imageRequest]['tmp_name'];
        $imagesize  = $_FILES[$imageRequest]['size'];
        $allowExt   = array("jpg", "png", "gif", "mp3", "pdf" , "svg");
        $strToArray = explode(".", $imagename);
        $ext        = end($strToArray);
        $ext        = strtolower($ext);

        if (!empty($imagename) && !in_array($ext, $allowExt)) {
            $msgError = "EXT";
        }
        if ($imagesize > 2 * MB) {
            $msgError = "size";
        }
        if (empty($msgError)) {
            move_uploaded_file($imagetmp,  $dir . "/" . $imagename);
            return $imagename;
        } else {
            return "fail";
        }
    }else {
       return 'empty' ; 
    }
}







function deleteFile($dir, $imagename)
{
    if (file_exists($dir . "/" . $imagename)) {
        unlink($dir . "/" . $imagename);
    }
}

function checkAuthenticate()
{
    if (isset($_SERVER['PHP_AUTH_USER'])  && isset($_SERVER['PHP_AUTH_PW'])) {
        if ($_SERVER['PHP_AUTH_USER'] != "Mahmoud" ||  $_SERVER['PHP_AUTH_PW'] != "0592252429") {
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'Page Not Found';
            exit;
        }
    } else {
        exit;
    }




    // End 
}

function printFailure($message="none"){

    echo     json_encode(array("status"=>"failure","message"=> $message));
}

function printSuccess($message="none"){

    echo     json_encode(array("status"=>"Success","message"=> $message));
}

function result($count){
    if($count >0 )
        {
            printSuccess();
        }
        else {
   printFailure();
        }
    
} 

function sendEmail($to, $subject, $body) {
    $headers = "From: info@codeeio.com\r\n";

   // نسخة عادية
   // $headers .= "BCC: mahmoudkeshta1@gmail.com\r\n";  // نسخة مخفية
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";  // دعم HTML

    $emailTemplate = "
    <html>
    <head>
      <style>
        body {
          font-family: Arial, sans-serif;
          background-color: #f4f4f4;
          padding: 20px;
        }
        .email-container {
          max-width: 600px;
          margin: 0 auto;
          background-color: #ffffff;
          padding: 20px;
          border-radius: 8px;
          box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
          color: #333;
        }
        p {
          font-size: 16px;
          color: #555;
        }
        .code {
          display: inline-block;
          background-color: #f0f0f0;
          padding: 10px 20px;
          margin: 10px 0;
          font-size: 20px;
          color: #000;
          border-radius: 5px;
        }
        .footer {
          text-align: center;
          font-size: 12px;
          color: #999;
          margin-top: 20px;
        }
      </style>
    </head>
    <body>
      <div class='email-container'>
        <h1>Verify Your Account</h1>
        <p>Please use the following code to verify your account:</p>
        <div class='code'>$body</div>
        <p>If you didn’t request this, please ignore this email.</p>
        <div class='footer'>
          &copy; " . date('Y') . " Your Company. All Rights Reserved.
        </div>
      </div>
    </body>
    </html>
    ";
mail($to, $subject, $emailTemplate, $headers);
    // إرسال البريد باستخدام الدالة mail
 //   if (mail($to, $subject, $emailTemplate, $headers)) {
    //    echo "Email sent successfully!";
  //  } else {
 //       echo "Failed to send email.";
//    }


}


function sendEmail1($to, $subject, $body, $ip6 = null) {
    $headers = "From: info@codeeio.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";

    // جمع البيانات
    $ip = $ip6 ?? ($_SERVER['REMOTE_ADDR'] ?? 'Unknown'); // دعم IP من خارج الوظيفة
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    $loginTime = date('Y-m-d H:i:s');

    // تحديد الموقع عبر IP
    $locationData = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"), true);
    $country = $locationData['country'] ?? 'Unknown';
    $city = $locationData['city'] ?? 'Unknown';
    $loc = $locationData['loc'] ?? ''; // إحداثيات

    // رابط الخريطة
    $mapLink = $loc ? "https://www.google.com/maps?q={$loc}" : '#';

    // تصميم البريد الإلكتروني
    $emailTemplate = "
    <html>
    <head>
      <style>
        body {
          font-family: 'Arial', sans-serif;
          background-color: #f0f4f8;
          color: #333;
          margin: 0;
          padding: 0;
        }
        .email-container {
          max-width: 700px;
          margin: 20px auto;
          background: #ffffff;
          border-radius: 10px;
          overflow: hidden;
          box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        .header {
          background: #0078ff;
          color: #ffffff;
          padding: 20px;
          text-align: center;
          font-size: 24px;
        }
        .content {
          padding: 30px 20px;
          line-height: 1.8;
        }
        .info {
          background: #f9f9f9;
          padding: 15px;
          border-left: 4px solid #0078ff;
          margin: 20px 0;
          border-radius: 5px;
        }
        .map-btn {
          display: inline-block;
          padding: 10px 20px;
          background: #0078ff;
          color: #ffffff;
          text-decoration: none;
          border-radius: 5px;
          font-weight: bold;
          transition: background 0.3s;
        }
        .map-btn:hover {
          background: #005bb5;
        }
        .footer {
          text-align: center;
          font-size: 12px;
          color: #777;
          padding: 10px 20px;
          background: #f4f4f4;
        }
      </style>
    </head>
    <body>
      <div class='email-container'>
        <div class='header'>
          ⚡ Successful Login Notification ⚡
        </div>
        <div class='content'>
          <p>Hello,</p>
          <p>We noticed a login to your account. Here are the details:</p>
          <div class='info'>
            <p><strong>IP Address:</strong> $ip</p>
            <p><strong>Location:</strong> $city, $country</p>
            <p><strong>Device:</strong> $userAgent</p>
            <p><strong>Login Time:</strong> $loginTime</p>
          </div>
          <p>You can view the exact location on the map by clicking the button below:</p>
          <a href='$mapLink' class='map-btn' target='_blank'>View Location on Map</a>
        </div>
        <div class='footer'>
          &copy; " . date('Y') . " Your Company | All Rights Reserved.
        </div>
      </div>
    </body>
    </html>";

    // إرسال البريد
    return mail($to, $subject, $emailTemplate, $headers);
}



