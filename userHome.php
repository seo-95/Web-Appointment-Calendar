<?php
	include('session.php'); //function for setting session data
	
	setCookieSession(); //set initial data for the session to be started
	session_start();
	//check if already logged in
    if(!isLogged())
	{
        header("Location: login.php");
        exit;
    }
    checkTimeOut("login.php"); //check if still valid session
	makeSecure(); //check if HTTPS is active on this page
	checkActiveCookies(); // check if Cookie are enabled
    
    session_write_close(); //close session data
?>

<!DOCTYPE html>
<html lang = "en-US">
	<head>
		<title> User home page </title>	
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
			<a href="#userHome.php">Home</a>
			<a href="userHome.php">Refresh</a>
			<a href="logout.php">Logout</a>
		</div>
		<?php include('handlers/errors_handler.php'); ?>
		<div class="main">
				<h2><center><span style="background-color: #000000; "><font color="white">Booking calendar for the week:</font></span></center></h2>
				<?php 
					include('handlers/table_handler.php');
					printTable(); 
				?>
				<div align="center" class="container">
					&ensp;<input type="button" value="-" onclick="minus()">
					<input type="text" style="width:12%" title="insert here the # of slots you want to book" maxlength="2" value="6" id="num_slot">
					<input type="button" value="+" onclick="plus()">

					<br>
					<input type="button" value="Delete" onclick="goingToDel()" name="delete">
					<br><br>
				</div>
		</div>
		
		<script src="js/tableEvent.js"></script>
		<script src="js/plusMinusBtn.js"></script>
			
	</body>
</html>