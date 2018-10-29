<?php
        session_start();
        if(!isset($_COOKIE['cookie_watcher']))
		{
			echo "<h1>Please, enable cookies to visit this website!</h1>";
			echo "<a href='index.php'>Home</a>";
			exit;
		}
        else
		{
            header('Location: ' .$_COOKIE['cookie_watcher']);
			exit;
		}
        
        session_write_close();        
?>