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
	
	// Store Posted Variables
	$username = cleanse ($_POST['username']);
	$password = cleanse ($_POST['password']);
	
	// Get IP address of logged in member
	$ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
	
	// Setup Member Log
	$action = 'logged in';
	
	// Validate Posted Variables
	validate(array($username, $password));
	
	// Determine who Logged In
	$member = mysql_fetch_array(mysql_query("SELECT id, username, password, salt FROM members WHERE username='$username' OR email='$username'"));
	$password_encrypted = $member['salt'] . hash('sha256', $member['salt'] . $password);
	$memberid = $member['id'];
	
	// Check if user submitted email instead of password
	if ($username == $member['email']) $username = $member['username'];
	
	// Login if username and password are correct
	if ($username == $member['username'] && $password_encrypted == $member['password']) {
		
		// Setup Session Variables
		$_SESSION['id'] = session_id();
		$_SESSION['memberid'] = $memberid;
		$_SESSION['auth'] = 'okay';
		
		if ( $username == 'hochmj' )
		{
			$_SESSION['isofficer'] = 'yes';
		}
		else
		{
			$_SESSION['isofficer'] = 'no';
		}
		$_SESSION['isstudent'] = 'no';
		
		// Add to the Members Logs
		mysql_query("INSERT INTO logs (memberid, action, ip) VALUES ('$memberid', '$action', '$ip')");
		
		// Close connection to the MySQL Database
		mysql_close($connection);
		
		// Login to Members Area
		header("location: http://dulynoted.union.rpi.edu/members/index.php");
	
	// Return Error if login failed
	} else {
		
		// Destroy Session Variables
		session_unset();
		
		// Close connection to the MySQL Database
		mysql_close($connection);
		
		// Return Error
		header ("location: http://dulynoted.union.rpi.edu/members/includes/error.php?error=You've entered an invalid username and/or password!");
		
	}
	
?>
