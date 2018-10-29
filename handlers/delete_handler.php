<?php

	include('../session.php'); //function for setting session data

	setCookieSession(); //set initial data for the session to be started
	session_start();
	//check if already logged in
	if(!isLogged())
	{
		header("Location: ../login.php");
		exit;
	}
	checkTimeOut("../login.php"); //check if still valid session
	makeSecure(); //check if HTTPS is active on this page
	checkActiveCookies(); // check if Cookie are enabled
	
	session_write_close(); //close session data

	
	include('db_handler.php');
	
	$conn = db_conn();
	if(!$conn)
	{
		echo "<p>Something bad occured while processing the request <p>";
		echo "<a href='../index.php>Home</a>'";
		exit;
	}
	$errors = array();
	//sleep(10);
	if(tryDelete($conn, $_SESSION['s249896_email']))
	{ 
		mysqli_close($conn);
		header('HTTP/1.1 303 See Other');
		header('Location: ../userHome.php');
		exit;
	}
	else
	{ 
		mysqli_close($conn);
		array_push($errors, "You do not have any booked slot that can be deleted");
		session_start();
		$_SESSION['errors'] = $errors;;
		session_write_close();
		header('HTTP/1.1 303 See Other');
		header('Location: ../userHome.php');
		exit;
	}
	
	exit;
	

?>