<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - ConnectCore</title>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-header">
                    <i class="fas fa-network-wired"></i>
                    <h1>ConnectCore</h1>
                    <p>Create your company account</p>
                </div>

                <?php if(isset($_SESSION['error_message'])): ?>
                    <div class="alert error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php 
                            echo $_SESSION['error_message'];
                            unset($_SESSION['error_message']);
                        ?>
                    </div>
                <?php endif; ?>

                <form action="./utilities/verify_reg.php" method="POST" class="auth-form">
                    <div class="form-group">
                        <label for="name">
                            <i class="fas fa-building"></i> Company Name
                        </label>
                        <input type="text" id="name" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="industry">
                            <i class="fas fa-industry"></i> Industry
                        </label>
                        <input type="text" id="industry" name="industry" required>
                    </div>

                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i> Email
                        </label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="password">
                            <i class="fas fa-lock"></i> Password
                        </label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">
                            <i class="fas fa-phone"></i> Company Phone
                        </label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>

                    <div class="form-group">
                        <label for="address">
                            <i class="fas fa-map-marker-alt"></i> Company Address
                        </label>
                        <input type="text" id="address" name="address" required>
                    </div>

                    <button type="submit" name="register" class="auth-btn">
                        <i class="fas fa-user-plus"></i> Register
                    </button>

                    <p class="auth-links">
                        Already have an account? <a href="login.php">Login</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
