<?php
	
	// Enable GZIP Compression
	include "/home/dulynote/public_html/members/includes/gzip_compress.php";
	
	// Connect to the MySQL Database on the PHP Server
	session_start();
	include "connect.php";
	
	// Form Validation
	include "validate.php";
	
	// Clean Bad Characters in Variables
	include "cleanse_variable.php";
	
	// Determine who logged out
	$username = cleanse ($_SESSION['username']);
	$memberid = cleanse ($_SESSION['memberid']);
	
	// Validate Posted Variables
	validate(array($username, $memberid));
	
	// Get IP address of logged in member
	$ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
	
	// Setup Member Log
	$action = 'logged out';
	
	// Add to the Members Logs
	mysql_query("INSERT INTO logs (memberid, action, ip) VALUES ('$memberid', '$action', '$ip')");
	
	// Destroy Session Variables
	session_unset();
	session_destroy();
	
	// Close Connection to the MySQL Database
	mysql_close($connection);
	
	// Redirect to the Login Page
	header("location: http://dulynoted.union.rpi.edu/members/login.php");
	
?>
