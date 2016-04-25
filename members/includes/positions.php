<?php
	
	/* Store Members Positions into Array */
	
	// Start out with Empty Array
	$position[0] = '';
	
	// Search for and fetch Member(s) from the MySQL Database
	$member_search = mysql_query("SELECT id, position FROM members ORDER BY id");
	
	// Store Member Names and IDs into Array
	while ($members = mysql_fetch_array($member_search)) $position[$members['id']] = $members['position'];
	
?>
