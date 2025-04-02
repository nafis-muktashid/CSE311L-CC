<?php

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>ConnectCore</title>
		<link rel="stylesheet" href="./css/loginForm.css" />
	</head>
	<body>
		<div class="container">
			<div class="form-box" id="login-form">
				<form action="login.php" method="POST">
					<h2>ConnectCore</h2>
					<input type="email" name="email" placeholder="Email" required />
					<input type="password" name="password" placeholder="Password" required />
					<button type="submit" name="login">Login</button>
					<p> New to ConnectCore? <a href="reg.php">Register</a>
					</p>
				</form>
			</div>
		</div>

	</body>
</html>
