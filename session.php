<?php

	function setCookieSession()
	{
		$session_name= "s249896_user-session";
		//make sure, that the session id is sent only with a cookie, and not in the url
		ini_set('session.use_only_cookies', 1);
		session_name($session_name);
	}
	
	
	function checkTimeOut($redirect)
	{
		// 2 minutes session
		if(time() - $_SESSION['s249896_timestamp'] > 2*60) 
		{
			unset($_SESSION['s249896_email'], $_SESSION['s249896_password'], $_SESSION['s249896_timestamp']);
			$_SESSION['s249896_logged_in'] = false;
			session_destroy();
			$redirect= $redirect .'?msg=SessionTimeOut';
			echo "redirect: ".$redirect;
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: ' . $redirect);
			exit;
		} 
		else
			$_SESSION['s249896_timestamp'] = time(); //set new timestamp
	}
	
	function makeSecure()
	{
		if ( !isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] ) !== "on" ) 
		{
			$redirect='https://' .$_SERVER['HTTP_HOST'] .$_SERVER['REQUEST_URI'];
			echo "redirect: ".$redirect;
			header('HTTP/1.1 301 Moved Permanently');
			session_write_close();
			header('Location: ' . $redirect);
			exit;
		}
	}
	
	function checkActiveCookies()
	{
		setcookie('cookie_watcher', 'index.php');
        if(!isset($_COOKIE['cookie_watcher']))
		{
			header('Location: checkCookie.php');
			exit;
		}
        session_write_close();
	}
	
	function isLogged()
	{
		return ( isset($_SESSION['s249896_logged_in']) && $_SESSION['s249896_logged_in'] == true );
	}
	
	function makeNoSecure()
	{
		if ( isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] ) == "on" ) 
		{
			$redirect='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			echo "redirect: ".$redirect;
			header('HTTP/1.1 301 Moved Permanently');
			session_write_close();
			header('Location: ' . $redirect);
			exit;
		}
	}
?>














