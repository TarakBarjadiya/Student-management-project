<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Reset Password - Student </title>
    <link rel="stylesheet" href="css/style.css">
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const params = new URLSearchParams(window.location.search);
            if (params.get('error') === 'invalid_details') {
                alert('Invalid Username or Password.');
            }
            if (params.get('error') === 'password_mismatch') {
                alert('Both password must be same.');
            }
        });
    </script>
</head>

<body>

    <div class="wrapper">
        <div class="title-text">
            <div class="title login">Reset Password</div>
        </div>
        <div class="form-container">
            <div class="form-inner">
                <form action="handlePassChange.php" method="post" class="login">
                    <div class="field">
                        <input type="email" placeholder="Email Address" name="email" title="Please enter an correct email address" required>
                    </div>
                    <div class="field">
                        <input type="password" placeholder="New Password" name="new_password" required>
                    </div>
                    <div class="field">
                        <input type="password" placeholder="Confirm Password" name="confirm_password" required>
                    </div>
                    <div class="show-pass">
                        <input id="showpass" type="checkbox">
                        <label for="showpass">Show Password</label>
                    </div>
                    <div class="field">
                        <input type="submit" value="Reset Password">
                    </div>
                    <a href="login.php" class="forgot-link">Back to Login</a>
                    <div class="signup-link">
                        <a href="../index.php">Back to Website</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="./js/login.js"></script>
</body>

</html>