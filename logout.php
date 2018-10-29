<?php
	include('session.php');
	
	if(empty($_SESSION['s249896_logged_in']))
	{
		setCookieSession();
		session_start();
		if(!isLogged())
		{
			header('Location: login.php');
			exit;
		}
		$_SESSION['s249896_logged_in'] = false;
		session_unset(); // svuota la sessione
		session_destroy(); // distrugge la sessione
		// HTTP: no data to protect outside the user area
		$redirect='login.php';
		echo "redirect: ".$redirect;
		header('HTTP/1.1 303 See Other');
		header('Location: ' . $redirect);
		exit();
	}
	
?>