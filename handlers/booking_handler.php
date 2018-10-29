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
	
	if(!isset($_COOKIE['slots']))
	{
		echo "<h1>An error occuring while processing the request</h1>";
		echo "<a href='../index.php'>Home</a>";
		exit;
	}
	$cookie_str = urldecode($_COOKIE['slots']);
	// pieces[i] = 'Day_slot'
	$pieces = explode("|", $cookie_str);
	// book_array['Day'] = (slot1, slot2, ..., slotN)
	$book_array = array();
	for($index = 0; $index < count($pieces); $index ++)
	{
		$tmp = array();
		$tmp = explode("_", $pieces[$index]);
		if(!isset($book_array[$tmp[0]]))
			$book_array[$tmp[0]] = array();
		array_push($book_array[$tmp[0]], $tmp[1]);
	}
	//print_r($book_array);
	
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
	if(tryBook($conn, $_SESSION['s249896_email'], $book_array))
	{ 
		mysqli_close($conn);
		header('HTTP/1.1 303 See Other');
		header('Location: ../userHome.php');
		exit;
	}
	else
	{ 
		mysqli_close($conn);
		array_push($errors, "Sorry but the selected slots are no more available");
		session_start();
		$_SESSION['errors'] = $errors;;
		session_write_close();
		header('HTTP/1.1 303 See Other');
		header('Location: ../userHome.php');
		exit;
	}
	
	exit;

?>















