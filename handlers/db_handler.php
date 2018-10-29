<?php
	define('DB_SERVER', 'localhost:3306');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', '');
	define('DB_DATABASE', 'registration');
	
	function db_conn()
	{
		$conn = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
		return $conn;
	}
	
	function tryLogin($conn, $email, $password)
	{
		if(!$conn)
		{
			echo "<p>Something bad occured while processing the request <p>";
			echo "<a href='../index.php>Home</a>'";
			exit;
		}
		$safe_email = mysqli_real_escape_string($conn, $email);
		$user_pass_query = "SELECT email, password FROM users WHERE email = '$safe_email'";
		$res = mysqli_query($conn, $user_pass_query);
		if(mysqli_num_rows($res) == 1)
		{
			$row = mysqli_fetch_array($res, MYSQLI_ASSOC);
			if($row['password'] == $password)
				return true;
			else
				return false;
		}
		else
			return false;
	}
	
	//atomic user insertion
	function tryInsertUser($conn, $email, $password)
	{
		if(!$conn)
		{
			echo "<p>Something bad occured while processing the request <p>";
			echo "<a href='../index.php>Home</a>'";
			exit;
		}
		//start transaction
		mysqli_autocommit($conn, false);
		$safe_email = mysqli_real_escape_string($conn, $email);
		//lock the entire users table to avoid concurrent duplicate users subscription
		$sql_lock = "SELECT * FROM users FOR UPDATE";
		$res = mysqli_query($conn, $sql_lock);
		if( !$res )
		{
			echo "" .mysqli_error($conn) ." - Please contact the webmaster";
			mysqli_rollback($conn);
			mysqli_autocommit($conn, true);
			mysqli_close($conn);
			exit;
		}
		
		$sql_count = "SELECT COUNT(*) as total FROM users";
		$res = mysqli_query($conn, $sql_count);
		if( !$res )
		{
			echo "" .mysqli_error($conn) ." - Please contact the webmaster";
			mysqli_rollback($conn);
			mysqli_autocommit($conn, true);
			mysqli_close($conn);
			exit;
		}
		$count = mysqli_fetch_array($res, MYSQLI_ASSOC);
		if($count['total'] >= 10) //the maximum #of users was already reached
		{
			mysqli_rollback($conn);
			mysqli_autocommit($conn, true);
			echo "<p>Maximum number of users already reached<p>";
			echo "<a href='../index.php>Home</a>'";
			mysqli_close($conn);
			exit;
		}
		//check if user already exists
		$sql_count = "SELECT COUNT(*) as total FROM users WHERE email='$safe_email'";
		$res = mysqli_query($conn, $sql_count);
		if( !$res )
		{
			echo "" .mysqli_error($conn) ." - Please contact the webmaster";
			mysqli_rollback($conn);
			mysqli_autocommit($conn, true);
			mysqli_close($conn);
			exit;
		}
		$count = mysqli_fetch_array($res, MYSQLI_ASSOC);
		if($count['total'] > 0) //the maximum #of users was already reached
		{
			mysqli_rollback($conn);
			mysqli_autocommit($conn, true);
			return false;
		}
		//insert the user
		$sql_ins_user = "INSERT INTO users (email, password) 
					VALUES ('$safe_email', '$password')";
		$res = mysqli_query($conn, $sql_ins_user);
		if( !$res )
		{ 
			echo "" .mysqli_error($conn) ." - Please contact the webmaster";
			mysqli_rollback($conn);
			mysqli_autocommit($conn, true);
			mysqli_close($conn);
			exit;
		}
		/*associate a color for the new user. I don't need to check if the email is already present because it is a unique attribute
			for the table colors*/
		$sql_color = "UPDATE colors as C1 SET C1.email='$safe_email'
						WHERE C1.colorRGB =	((SELECT * FROM(SELECT MIN(colorRGB) from colors WHERE email IS NULL) t))";			
		$res = mysqli_query($conn, $sql_color);
		if( !$res )
		{ 
			echo "" .mysqli_error($conn) ." - Please contact the webmaster";
			mysqli_rollback($conn);
			mysqli_autocommit($conn, true);
			mysqli_close($conn);
			exit;
		}
		if(mysqli_commit($conn))
		{
			mysqli_autocommit($conn, true);
			return true;
		}
		echo "" .mysqli_error($conn) ." - Please contact the webmaster";
		mysqli_rollback($conn);
		mysqli_autocommit($conn, true);
		exit;
	}
	
	
	function getBookingColor($conn)
	{
		if(!$conn)
		{
			echo "<p>Something bad occured while processing the request <p>";
			echo "<a href='../index.php>Home</a>'";
			exit;
		}
		// no need for concurrency here. If a user is signing up the users table will be locked
		$sql_book = "SELECT dayOfWeek, slot, C.colorRGB as color FROM bookings B, users U, colors C
					WHERE B.email = U.email AND C.email = U.email";
		$res = mysqli_query($conn, $sql_book);
		if(!$res)
		{
			echo "" .mysqli_error($conn) ."Please contact the webmaster";
			exit;
		}
		$mapping = array();
		while( ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) != NULL)
		{
			$mappings[$row['dayOfWeek']] = array();
			$mapping[$row['dayOfWeek']][$row['slot']] = $row['color'];
		}
		return $mapping;
	}
	
	function getUserColor($conn, $email)
	{
		if(!$conn)
		{
			echo "<p>Something bad occured while processing the request <p>";
			echo "<a href='../index.php>Home</a>'";
			exit;
		}
		$safe_email = mysqli_real_escape_string($conn, $email);
		// no need for concurrency
		$sql_col = "SELECT colorRGB FROM colors
					WHERE email = '$safe_email'";
		$res = mysqli_query($conn, $sql_col);
		if(!$res)
		{
			echo "" .mysqli_error($conn) ."Please contact the webmaster";
			exit;
		}
		$row = mysqli_fetch_array($res, MYSQLI_ASSOC);
		return $row['colorRGB'];
	}
	
	function tryBook($conn, $email, $book_array)
	{
		if(!$conn)
		{
			echo "<p>Something bad occured while processing the request <p>";
			echo "<a href='../index.php>Home</a>'";
			exit;
		}
		$sql_whereClause = composeWhereClause($conn, $book_array);
		//echo($sql_tablock); exit;
		
		//start transaction
		mysqli_autocommit($conn, false);
		//lock the requested rows
		$sql_tablock = "SELECT * FROM bookings " .$sql_whereClause ."FOR UPDATE";
		$res = mysqli_query($conn, $sql_tablock);
		if( !$res )
		{
			echo "" .mysqli_error($conn) ." - Please contact the webmaster";
			mysqli_rollback($conn);
			mysqli_autocommit($conn, true);
			mysqli_close($conn);
			exit;
		}
		
		$sql_alreadyBooked = "SELECT COUNT(*) as total FROM bookings " .$sql_whereClause ." AND email IS NOT NULL";
		$res = mysqli_query($conn, $sql_alreadyBooked);
		if( !$res )
		{
			echo "" .mysqli_error($conn) ." - Please contact the webmaster";
			mysqli_rollback($conn);
			mysqli_autocommit($conn, true);
			mysqli_close($conn);
			exit;
		}
		$count = mysqli_fetch_array($res, MYSQLI_ASSOC);
		if($count['total'] > 0)
		{
			mysqli_rollback($conn);
			mysqli_autocommit($conn, true);
			return false;
		}
		
		//$col = getUserColor($conn, $email);
		//book the requested slots
		$safe_email = mysqli_real_escape_string($conn, $email);
		$sql_book = "UPDATE bookings SET email='$safe_email', t_id=now() " .$sql_whereClause;
		$res = mysqli_query($conn, $sql_book);
		if( !$res )
		{
			echo "" .mysqli_error($conn) ." - Please contact the webmaster";
			mysqli_rollback($conn);
			mysqli_autocommit($conn, true);
			mysqli_close($conn);
			exit;
		}
		//everything gone well
		mysqli_commit($conn);
		mysqli_autocommit($conn, true);
		return true;
	}
	
	function tryDelete($conn, $email)
	{
		if(!$conn)
		{
			echo "<p>Something bad occured while processing the request <p>";
			echo "<a href='../index.php>Home</a>'";
			exit;
		}
		//start transaction
		mysqli_autocommit($conn, false);
		//lock the table to avoid insertio during delete
		$sql_lastbook = "SELECT * FROM bookings FOR UPDATE";
		$res = mysqli_query($conn, $sql_lastbook);
		if( !$res )
		{
			echo "" .mysqli_error($conn) ." - Please contact the webmaster";
			mysqli_rollback($conn);
			mysqli_autocommit($conn, true);
			mysqli_close($conn);
			exit;
		}	
		$safe_email = mysqli_real_escape_string($conn, $email);
		$sql_countbook = "SELECT COUNT(*) as total FROM bookings WHERE email='$safe_email'";
		$res = mysqli_query($conn, $sql_countbook);
		if( !$res )
		{
			echo "" .mysqli_error($conn) ." - Please contact the webmaster";
			mysqli_rollback($conn);
			mysqli_autocommit($conn, true);
			mysqli_close($conn);
			exit;
		}	
		$count = mysqli_fetch_array($res, MYSQLI_ASSOC);
		if($count['total'] == 0)
		{
			mysqli_rollback($conn);
			mysqli_autocommit($conn, true);
			return false; //no deletable slots for that user
		}
		// PROBLEM: order by day -> not lexicographical order !
		$sql_selectlast = "SELECT MAX(t_id) as last_t from bookings WHERE email='$safe_email'";
		$res = mysqli_query($conn, $sql_selectlast);
		if( !$res )
		{
			echo "" .mysqli_error($conn) ." - Please contact the webmaster";
			mysqli_rollback($conn);
			mysqli_autocommit($conn, true);
			mysqli_close($conn);
			exit;
		}	
		//take only the first row that corresponds to the last booked slot
		$row = mysqli_fetch_array($res, MYSQLI_ASSOC);
		//finally delete the booking
		$sql_delbook = "UPDATE bookings SET email=null, t_id=null WHERE t_id='" .$row['last_t'] ."' AND email='$safe_email'";
		$res = mysqli_query($conn, $sql_delbook);
		if( !$res )
		{
			echo "" .mysqli_error($conn) ." - Please contact the webmaster";
			mysqli_rollback($conn);
			mysqli_autocommit($conn, true);
			mysqli_close($conn);
			exit;
		}	
		mysqli_commit($conn);
		mysqli_autocommit($conn, true);
		return true;
	}
	

	function composeWhereClause($conn, $book_array)
	{
		$query = "WHERE (";
		
		foreach ($book_array as $day => $slot_arr) 
		{
			$query = $query ."( dayOfWeek='" .mysqli_real_escape_string($conn, $day) ."' AND (";
			foreach($slot_arr as $slot)
			{
				$query = $query ."slot='" .mysqli_real_escape_string($conn, $slot) ."'";
				$query = $query ." OR ";
			}
			//delete the last pending " OR" but leave the last space
			$query = substr($query, 0, strlen($query)-3);
			$query = $query . ") ) OR ";
		}
		//delete the last pending " OR" but leave the last space
		$query = substr($query, 0, strlen($query)-3);
		$query = $query .") ";
		
		return $query;
	}
?>







