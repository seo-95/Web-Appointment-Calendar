<?php
	define('NUM_SLOT', 10);
	define('FIRST_SLOT', 8);
	define('LAST_SLOT', 17);
	define('DAYS', array('Monday','Tuesday', 'Wednesday', 'Thursday', 'Friday'));
	
	function printTable()
	{
		include('db_handler.php');
		$conn = db_conn();
		if(!$conn)
			return false; //@TODO: maybe errors
		
		// return mapping['day']['slot'] = 'color'
		$mapping = getBookingColor($conn);
		
		//echo "" .$nextFreeNum; exit;
		echo "<table id='table_calendar' style='margin:auto'>";  
		echo "<tr>";
		//the void upper left cell
		echo "<th style='background:transparent';></th>";
		for($curr_day = 0; $curr_day < count(DAYS); $curr_day ++)
			echo "<th>" .DAYS[$curr_day] ."</th>";
			//echo "<th>" .$slot .":00" ."</th>";
		echo "</tr>";
		
		for($curr_slot = FIRST_SLOT; $curr_slot <= LAST_SLOT; $curr_slot ++)
		{
			echo "<tr>";
			echo "<th width='5%'>" .$curr_slot ."</th>";
			for($curr_day = 0; $curr_day < count(DAYS); $curr_day ++)
			{
				$next = $curr_slot+1;
				$id = "" .DAYS[$curr_day] ."_" .$curr_slot;
				if( isset($mapping[DAYS[$curr_day]]) && isset($mapping[DAYS[$curr_day]][$curr_slot]) )
				{
					$title="Slot booked-> " .DAYS[$curr_day] .'[' .$curr_slot .':00-' .$next .':00]';
					$col = $mapping[DAYS[$curr_day]][$curr_slot];
				}
				else
				{
					$title = DAYS[$curr_day] .'[' .$curr_slot .':00-' .$next .':00]';;
					$col = 'white';
				}
				
				setColoredCell($title, $col, $id);
			}
				
			echo "</tr>";
		}
		echo "</table>";
	}
	
	function setColoredCell($title, $col, $id)
	{
		// if user is logged in defined it adds the script for the hover effect
		if(isset($_SESSION['s249896_logged_in']) && isset($_SESSION['s249896_email']) && isset($_SESSION['s249896_color']) )
		{
			if(strcmp($col, 'white') == 0)
			{
				$script = "onmouseover='greenOrRed(this)' onmouseleave='resetColor()' onclick='book()'";	
				echo "<td id='$id' title='$title' class='blank' $script></td>\n";
			}
			else
				if( $_SESSION['s249896_color'] == $col ) //if belongs to users it prints email also
					echo "<td id='$id' title='$title' class='userSlot' style='background-color:$col'>".$_SESSION['s249896_email']."</td>";
			else //@TODO add script
				echo "<td id='$id' title='$title' style='background-color:$col;'></td>\n";
		}
		else //no logged in ==> no script
			echo "<td id='$id' title='$title' style='background-color:$col'></td>\n";
	}




?>



























