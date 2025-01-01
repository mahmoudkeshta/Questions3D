<?php
// دالة لإرسال طلب POST باستخدام cURL
function sendPostRequest($url, $data) {
    $ch = curl_init();

    // إعدادات cURL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

    // تنفيذ طلب cURL
    $response = curl_exec($ch);

    // التحقق من وجود أخطاء في cURL
    if (curl_errno($ch)) {
        echo 'خطأ: ' . curl_error($ch);
    }

    // إغلاق جلسة cURL
    curl_close($ch);

    // إرجاع الاستجابة من API
    return $response;
}

// متغيرات لتخزين رسائل الخطأ
$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // جمع البيانات من النموذج
    $username = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
    $oracle_code = filter_input(INPUT_POST, 'oracle_code', FILTER_SANITIZE_STRING);

    // تحقق من أن الحقول ليست فارغة
    if (empty($username) || empty($email) || empty($subject) || empty($oracle_code)) {
        $errorMessage = "جميع الحقول مطلوبة.";
    } else {
        // URL API
        $apiUrl = 'https://aletharsociety.com/edu/signup.php';

        // البيانات التي سيتم إرسالها إلى API
        $postData = [
            'name' => $username,
            'email' => $email,
            'subject' => $subject,
            'oracle_code' => $oracle_code
        ];

        // إرسال طلب POST إلى API
        $response = sendPostRequest($apiUrl, $postData);

        // فك تشفير الاستجابة (يفترض أن الاستجابة بتنسيق JSON)
        $responseData = json_decode($response, true);

        // التعامل مع الاستجابة من API
         if (isset($responseData['status']) && $responseData['status'] == 'success') {
            // إذا تم التسجيل بنجاح، نقوم بتحويل المستخدم إلى الصفحة الجديدة
            header('Location:./edu/m.php');
            exit();
        } else {
              //  alert("failure!");
           // $errorMessage = isset($responseData['message']) ? $responseData['message'] : "حدث خطأ غير معروف";
        }
    
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل المعلم</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #b18f5b;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            max-width: 500px;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            font-size: 14px;
        }
        .success {
            color: green;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>تسجيل المعلم</h2>
    
    <?php
    // إذا كانت هناك رسالة نجاح أو خطأ، عرضها
    if (isset($successMessage)) {
        echo "<p class='success'>$successMessage</p>";
    }
    if (!empty($errorMessage)) {
        echo "<p class='error'>$errorMessage</p>";
    }
    ?>

    <form method="POST" action="">
        <label for="name">الاسم</label>
        <input type="text" id="name" name="name" required>

        <label for="email">البريد الإلكتروني</label>
        <input type="email" id="email" name="email" required>

        <label for="subject">المادة</label>
        <input type="text" id="subject" name="subject" required>

        <label for="oracle_code">رمز أوراكل</label>
        <input type="text" id="oracle_code" name="oracle_code" required>

        <button type="submit">تسجيل</button>
    </form>

</div>

</body>
</html>