<?php
	
	/* Archive Past Events */
	
	// Search for and fetch Event(s) from the MySQL Database
	$result = mysql_query("SELECT id, summary, dtend FROM events WHERE archived=0 ORDER BY dtend");
	$ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
	
	// Check Events in Database
	while ($row = mysql_fetch_array($result)) {
		
		// Check if Event has already occurred
		$event_date = $row['dtend'];
		if ($event_date < date("Y-m-d H:i:s")) {
			
			// If yes, archive Event
			$id = $row['id'];
			$action = 'auto-archived event <a href="events.php?id=' . $row['id'] . '">' . $row['summary'] . '</a>';
			mysql_query("UPDATE events SET archived=1 WHERE id='$id'");
			mysql_query("INSERT INTO logs (memberid, action, ip) VALUES ('$ip', '$action', '$ip')");
			
		}
		
	}
	
?>
