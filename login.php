<?php
	include('session.php'); //function for setting session data
	
	setCookieSession(); //set initial data for the session to be started
    session_start();
	// check if already logged in
    if(isLogged())
	{
          header("Location: userHome.php");
          exit();
    }
	makeSecure(); //use HTTPS on this page
    checkActiveCookies(); // check if cookies are enabled
    session_write_close();
?>

<!DOCTYPE html>
<html lang = "en-US">
	<head>
		<title> Home </title>	
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="author" content="Matteo Senese">
		<link rel="stylesheet" href="style/style.css">
	</head>
	
	<!-- hide the page if js is disabled -->
	<noscript>
		<style>
			body { background-image: none !important }
			.sidenav, .main { display: none }
		</style>
		<h1>Please enable javascript</h1>
		<a href='index.php'>Home</a>
	</noscript>
	
	<body>
		<div class="sidenav">
				<a href="index.php">Home</a>
				<a href="#login.php">Log in</a>
				<a href="signup.php">Sign up</a>
		</div>
		<?php include('handlers/errors_handler.php'); ?>
		<form action="handlers/login_handler.php" method="post">
			<div class="main">
				<h1 style="text-align:center">Login:</h1>
				<div align="center" class="container">
					<input style="width:45%" type="text" name="email" maxlength="30" placeholder="email" required autofocus><br>
					<input style="width:45%" type="password" name="password" maxlength="30" placeholder="password" required><br>
					<input type="submit" value="continue" name="login"><br>
				</div>
			</div>
		</form> 
	</body>
	
</html>