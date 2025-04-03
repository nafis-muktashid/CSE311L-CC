<?php

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ConnectCore</title>
        <link rel="stylesheet" href="./css/loginform.css">
    </head>
    <body>

        <div class="container">
			<div class="form-box " id="register-form">
				<form action="./utilities/verify_reg.php" method="POST">
					<h2>ConnectCore</h2>
					<input type="text" name="name" placeholder="Company Name" required/>
					<input type="text" name="industry" placeholder="Industry" required/>
					<input type="email" name="email" placeholder="Email" required/>
					<input type="password" name="password" placeholder="Password" required/>
					<input type="number" name="phone" placeholder="Company Phone"/>
					<input type="text" name="address" placeholder="Company Address"/>
					<button type="submit" name="register">Register</button>
					<p>Already have an account? <a href="login.php">Login</a></p>
				</form>
			</div>
		</div>
    
    </body>
</html>