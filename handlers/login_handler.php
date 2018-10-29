<?php
	include('../session.php');
	
	setCookieSession(); //set initial data for the session to be started
	session_start();
	// check if already logged in
	if(!empty($_SESSION['s249896_logged_in']) && $_SESSION['s249896_logged_in'] == true)
	{
          header("Location: ../userHome.php");
          exit;
    }
	session_write_close();
	
	include('db_handler.php');
	$errors = array();
	if( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login']) && isset($_POST['email']) && isset($_POST['password']))
	{  
		$email = stripslashes(strip_tags($_POST['email']));
		$password = $_POST['password']; //strip_tags not needed
		if(empty($email))
			array_push($errors, "Email field is empty");
		if(empty($password))
			array_push($errors, "Password field is empty");
		if(count($errors) > 0)
		{ 
			session_start();
			$_SESSION['errors'] = $errors;
			session_write_close();
			header('HTTP/1.1 303 See Other');
			header('Location: ../login.php');
			exit;	
		}
		
		$conn = db_conn(); 
		if(!$conn)
		{ 
			array_push($errors,"DB connection failed: " .mysqli_connect_error()); 
			mysqli_close($conn);
			session_start();
			$_SESSION['errors'] = $errors;
			session_write_close();
			header('HTTP/1.1 303 See Other');
			header('Location: '.$_SERVER['PHP_SELF']);
			exit;
		}	
		$password = md5($_POST['password']);
		if(tryLogin($conn, $email, $password)) //login done!!
		{  
			session_start();
			$_SESSION['s249896_logged_in'] = true;
			$_SESSION['s249896_email'] = $email;
			$_SESSION['s249896_color'] = getUserColor($conn, $email);
			$_SESSION['s249896_timestamp'] = time();
			session_write_close();
			mysqli_close($conn);
			header('Location: ../userHome.php');
			exit;
		}
		else
			array_push($errors, "Incorrect email or password");
	}
	else
	{
		array_push($errors, "Some problem occured, please contact webmaster");
	}

	if(count($errors) > 0)
	{
		session_start();
		$_SESSION['errors'] = $errors;
		session_write_close();
		mysqli_close($conn);
		//header('HTTP/1.1 307 temporary redirect');
		header('HTTP/1.1 303 See Other');
		header('Location: ../login.php');
		exit;
	}
?>