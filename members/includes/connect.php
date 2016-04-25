<?php
	
	/* Connect to the MySQL Database on the PHP Server*/
	
	// Start Page Load Timer
	$load_start = microtime();
	$page_load = explode(" ", $load_start);
	$load_start = $page_load[1] + $page_load[0];
	
	// Setup PHP Server Connection
	$connection = mysql_connect("db.union.rpi.edu","dulynote_notes","4ryaKsTutY9u");
	if (!$connection) die ('Could not connect: ' . mysql_error());
	
	// Setup MySQL Database Connection
	mysql_select_db("dulynote_dulynoted", $connection);
	if (!@mysql_select_db('dulynote_dulynoted')) die ('Unable to locate database: ' . mysql_error());zz
	
?>
