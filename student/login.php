<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Student Login </title>
    <link rel="stylesheet" href="css/style.css">
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const params = new URLSearchParams(window.location.search);
            if (params.get('error') === 'invalid_details') {
                alert('Invalid Username or Password.');
            }
        });

        function togglePasswordVisibility() {
            const passwordField = document.querySelector('input[name="password"]');
            const showPassCheckbox = document.getElementById('showpass');

            // Toggle password visibility based on checkbox state
            if (showPassCheckbox.checked) {
                passwordField.type = 'text';
            } else {
                passwordField.type = 'password';
            }
        }
    </script>
</head>

<body>

    <div class="wrapper">
        <div class="title-text">
            <div class="title login">Student Login</div>
        </div>
        <div class="form-container">
            <div class="form-inner">
                <form action="handleLogin.php" method="post" class="login">
                    <div class="field">
                        <input type="text" placeholder="Enroll No. / Email Address / Mobile No." name="email" required>
                    </div>
                    <div class="field">
                        <input type="password" placeholder="Password" name="password" required>
                    </div>
                    <div class="show-pass">
                        <input id="showpass" type="checkbox" onchange="togglePasswordVisibility()">
                        <label for="showpass">Show Password</label>
                    </div>
                    <div class="field">
                        <input type="submit" value="Login">
                    </div>
                    <a href="resetPassword.php" class="forgot-link">Reset Password</a>
                    <div class="signup-link">
                        <a href="../index.php">Back to Website</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="js/login.js"></script>
</body>

</html>