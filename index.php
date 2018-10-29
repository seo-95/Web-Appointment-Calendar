<?php
	include('session.php'); //function for setting session data
	
	setCookieSession(); //set initial data for the session to be started
	
    session_start();
	//check if already logged in
    if(isLogged())
	{
        header("Location: userHome.php");
        exit;
    }	
	makeNoSecure(); //HTTPS not needed here
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
	
	
	<body style="background-image: url('img/san_fra.jpg')">
		<div class="sidenav">
			<a href="index.php">Home</a>
			<a href="login.php">Log in</a>
			<a href="signup.php">Sign up</a>
		</div>
		<div class="main">
			<h2><center><span style="background-color: #000000; "><font color="white">Booking calendar for the week:</font></span></center></h2>
			<?php 
				include('handlers/table_handler.php');
				printTable(); 
			?>
		</div>
	</body>
</html>















