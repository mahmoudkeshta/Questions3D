<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Login</title>
  
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <h2>Get Started</h2>
        <p>Let's get started by filling out the form below.</p>

        <!-- إضافة عنصر لعرض رسالة الخطأ -->
        <div class="error-message" style="color: red; display: none;"></div>

        <!-- نموذج تسجيل الدخول -->
        <form id="loginForm" method="POST" action="login.php">
            <input type="email" name="email" class="form-input" placeholder="Email" required>

            <div class="password-toggle">
                <input type="password" name="oracle_code" class="form-input" placeholder="Oracle Code" required>
                <button type="button" class="toggle-btn" onclick="togglePassword()">&#128065;</button>
            </div>

            <button type="submit" class="login-btn">Login</button>
        </form>

        <div class="divider">Or sign up with</div>
        <a href="./Signup.php" class="signup-link">Don't have an account? Sign Up here</a>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.querySelector('input[name="oracle_code"]');
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
        }

        // تحقق من وجود معلمة "error" في URL وعرض رسالة الخطأ
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('error') === 'invalid_credentials') {
            const errorDiv = document.querySelector('.error-message');
            errorDiv.textContent = "Invalid email or Oracle Code. Please try again!";
            errorDiv.style.display = 'block'; // عرض رسالة الخطأ
        } else if (urlParams.get('error') === 'empty_fields') {
            const errorDiv = document.querySelector('.error-message');
            errorDiv.textContent = "Please fill in all fields!";
            errorDiv.style.display = 'block'; // عرض رسالة الخطأ
        }
    </script>
</body>
</html>
