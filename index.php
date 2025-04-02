<?php

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>ConnectCore</title>
		<link rel="stylesheet" href="./css/loginForm.css">
	</head>
	<body>
		<div class="container">
			<div class="form-box" id="login-form">
				<form action="">
					<h2>ConnectCore</h2>
					<input type="email" name="email" placeholder="Email" required />
					<input type="password" name="password" placeholder="Password" required />
					<button type="submit" name="login">Login</button>
					<p>New to ConnectCore? <a href="#">Register</a></p>
				</form>
			</div>

			<div class="form-box" id="register-form">
				<form action="">
					<h2>ConnectCore</h2>
					<!-- name, industry_type, email, password, phone, address -->
					<input type="text" name="name" placeholder="Name" required />
					<input type="email" name="email" placeholder="Email" required />
					<input type="password" name="password" placeholder="Password" required />
					<button type="submit" name="login">Login</button>
					<p>New to ConnectCore? <a href="#">Register</a></p>
				</form>
			</div>
		</div>
	</body>
</html>
