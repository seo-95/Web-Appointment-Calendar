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
		<title> Signup Page </title>	
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="author" content="Matteo Senese">
		<link rel="stylesheet" href="style/style.css">
	</head>
	
	<body>
	
	<!-- hide the page if js is disabled -->
	<noscript>
		<style>
			body { background-image: none !important }
			.sidenav, .main { display: none }
		</style>
		<h1>Please enable javascript</h1>
		<a href='index.php'>Home</a>
	</noscript>
	
	<div class="sidenav">
			<a href="index.php">Home</a>
			<a href="login.php">Log in</a>
			<a href="#signup.php">Sign up</a>
	</div>
	<?php include('handlers/errors_handler.php'); ?>
	<form action="handlers/signup_handler.php" method="post">
		<div class="main">
			<h1 style="text-align:center">Sign up:</h1>
			<div align="center" class="container">
				
				<input style="width:45%" title="Must be a valid email address" type="text" name="email" maxlength="30" placeholder="email" pattern="\b[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}\b" required autofocus><br>
				<input style="width:45%" title="Must contains at least 2 special characters" type="password" name="password1" maxlength="30" placeholder="password" 
				pattern="[a-zA-Z0-9]*[^a-zA-Z0-9][a-zA-Z0-9]*[^a-zA-Z0-9].*"required><br>
				<input style="width:45%" type="password" name="password2" maxlength="30" placeholder="repeat password" required><br>
				<input type="submit" value="sign up" name="login" required><br>
			</div>
		</div>
	</form> 
	
	</body>
	
</html>



