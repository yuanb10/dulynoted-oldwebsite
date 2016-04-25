<?php
	
	/* Store Members Full Names into Array */
	
	// Start out with NO SOLOIST
	$solo[0] = 'Nobody';
	
	// Search for and fetch Member(s) from the MySQL Database
	$member_search = mysql_query("SELECT id, firstname, lastname FROM members ORDER BY id ASC");
	
	// Store Member Names and IDs into Array
	while ($members = mysql_fetch_array($member_search)) $solo[$members['id']] = $members['firstname'] . ' ' . $members['lastname'];
	
?>
