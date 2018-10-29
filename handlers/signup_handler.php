<?php
	function validate($email, $password)
	{
		if (!filter_var($name, FILTER_VALIDATE_EMAIL)) 
		{
			$nameErr = "Only letters and white space allowed"; 
		}
	}


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
	if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) && isset($_POST['password1']) && isset($_POST['password2']))
	{ 
		$email = strip_tags($_POST['email']);
		$password1 = $_POST['password1']; //strip_tags not needed
		$password2 = $_POST['password2']; //strip_tags not needed
		if(empty($email))
			array_push($errors, "Email field is empty");
		if(empty($password1))
			array_push($errors, "Password field is empty");
		if(empty($password2))
			array_push($errors, "Password must be repeated");
		if(count($errors) > 0)
		{  
			session_start();
			$_SESSION['errors'] = $errors;
			session_write_close();
			header('HTTP/1.1 303 See Other');
			header('Location: ../signup.php');
			exit;
		}
		//input validation
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
			array_push($errors, "Email is not well formed");
		if (!preg_match("/[a-zA-Z0-9]*[^a-zA-Z0-9][a-zA-Z0-9]*[^a-zA-Z0-9].*/", $password1)) 
			array_push($errors, "Password is not well formed");
		if(count($errors) > 0)
		{  
			session_start();
			$_SESSION['errors'] = $errors;
			session_write_close();
			header('HTTP/1.1 303 See Other');
			header('Location: ../signup.php');
			exit;
		}
		
		if($password1 != $password2)
		{ 
			array_push($errors, "The two passwords do not match");
			session_start();
			$_SESSION['errors'] = $errors;
			session_write_close();
			header('HTTP/1.1 303 See Other');
			header('Location: ../signup.php');
			exit;
		}	
		$password = md5($password1);
		
		$conn = db_conn(); 
		if(!$conn)
		{ 
			array_push($errors,"DB connection failed: " .mysqli_connect_error()); 
			session_start();
			$_SESSION['errors'] = $errors;
			session_write_close();
			mysqli_close($conn);
			header('HTTP/1.1 303 See Other');
			header('Location: ../signup.php');
			exit;
		}
		
		//atomic insertion
		if(tryInsertUser($conn, $email, $password))
		{
			mysqli_close($conn);
			header("Location: ../login.php?msg=".urlencode("User succesfully registered"));
			exit;
		}
		else
		{
			array_push($errors,"Email " .$email ." is already used!"); 
			session_start();
			$_SESSION['errors'] = $errors;
			session_write_close();
			mysqli_close($conn);
			header("Location: ../signup.php");
			exit;
		}
	}


?>




