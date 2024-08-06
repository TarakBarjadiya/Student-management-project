<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Admin Login </title>
    <link rel="stylesheet" href="css/style.css">
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const params = new URLSearchParams(window.location.search);
            if (params.get('error') === 'invalid_details') {
                alert('Invalid Username or Password.');
            }
        });
    </script>
</head>

<body>

    <div class="wrapper">
        <div class="form-wrapper sign-up">
            <form method="post" action="handlePasswordChange.php">
                <h2>Reset Your Password</h2>
                <div class="input-group">
                    <input type="email" required>
                    <label for="">Email</label>
                </div>
                <div class="input-group">
                    <input type="password" required>
                    <label for="">New Password</label>
                </div>
                <div class="input-group">
                    <input type="password" required>
                    <label for="">Confirm Password</label>
                </div>
                <button type="submit" class="btn">Change Password</button>
                <div class="sign-link">
                    <p>Remember Your Password? <a href="#" class="signIn-link">Sign In</a></p>
                </div>
            </form>
        </div>

        <div class="form-wrapper sign-in">
            <form method="post" action="handleLogin.php">
                <h2>Admin Login</h2>
                <div class="input-group">
                    <input type="text" name="email" required>
                    <label for="">Email / Username</label>
                </div>
                <div class="input-group">
                    <input type="password" name="password" required>
                    <label for="">Password</label>
                </div>
                <button type="submit" class="btn">Login</button>
                <div class="sign-link">
                    <p><a href="#" class="signUp-link">Forgot Password?</a></p>
                </div>
            </form>
        </div>
    </div>

    <script src="js/login.js"></script>
</body>

</html>