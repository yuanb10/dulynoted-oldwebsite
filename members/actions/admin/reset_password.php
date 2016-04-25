<?php /* Enable GZIP Compression */ include "/home/dulynote/public_html/members/includes/gzip_compress.php"; ?>
<?php /* Connect to MySQL Database on the PHP server */ session_start(); include "connect.php"; ?>
<?php /* Get Canvas Elements */ $canvas = mysql_fetch_array(mysql_query("SELECT logo, navbar, tray FROM canvas WHERE id=1")); ?>
<?php /* Form Validation */ include "validate.php"; ?>
<?php /* Clean Bad Characters in Variables */ include "cleanse_variable.php"; ?>
<?php /* Get IP Address of Logged-In Member */ include "ip_address.php"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	
	<head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="robots" content="noindex, nofollow" />
		<title>Your New Password - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
		<base href="http://dulynoted.union.rpi.edu/" />
		<link rel="search" type="application/opensearchdescription+xml" title="Duly Noted" href="search.xml" />
		<link href="atom/index.php" type="application/atom+xml" rel="alternate" title="Duly Noted A Cappella Feed" />
		<link href="style.css" type="text/css" rel="stylesheet" />
		<script type="text/javascript" src=".jquery.js"></script>
		<script type="text/javascript" src="script.js"></script>
		
	</head>
	
	<body>
		<div class="container">
			
			<!-- Duly Noted Logo -->
			<?php echo $canvas['logo'] . "\r"; ?>
			
			<!-- Navigation Menu -->
			<?php echo $canvas['navbar'] . "\r"; ?>
			
			<!-- Main Content of Page -->
			<div class="canvas">
				
				<?php
				
				// Store Posted Variables
				$username = cleanse ($_REQUEST['username']);
				$keycode = cleanse ($_REQUEST['keycode']);
				
				// Validate Posted Variables
				validate(array($username, $keycode));
				
				// Determine who reset their password
				$member = mysql_fetch_array(mysql_query("SELECT id, email, keycode, keystamp FROM members WHERE username='$username'"));
				
				// Find Member Info and Keycode Data
				$verify_keycode = $member['keycode'];
				$verify_keystamp = $member['keystamp'];
				$memberid = $member['id'];
				$email = $member['email'];
				
				// Return Error if username or keycode failed or if keycode expired already
				if ($keycode != $verify_keycode && ((time() - strtotime($verify_keystamp)) / 86400 >= 24)) header ("location: http://dulynoted.union.rpi.edu/members/includes/error.php?error=Your keycode has expired already!");
				else if ($keycode != $verify_keycode) header ("location: http://dulynoted.union.rpi.edu/members/includes/error.php?error=Your keycode is invalid!");
				else {
				
				// Declare variables for data manipulation
				$alphanumeric = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
				
				// Setup Member Log
				$action = 'reset his password';
				
				// Create New Password and Salt
				for($repeat = 0; $repeat < 16; $repeat++) $password .= substr($alphanumeric, rand(0,36), 1);
				$salt = substr(md5(uniqid(rand(), true)), 0, rand() % 22 + 10);
				$password_encrypted = $salt . hash('sha256', $salt . $password);
				
				// Update Member Password and Salt on the MySQL Database
				mysql_query("UPDATE members SET password='$password_encrypted', salt='$salt', keycode='', keystamp='0000-00-00 00:00:00' WHERE username='$username'");
				
				// Send Email to Member with New Password
				mail($email, "Your Password has been reset for Duly Noted", "Your password on the Duly Noted Members Area has been reset. Username: $username, Password: $password. Login <a href='http://dulynoted.union.rpi.edu/members/login.php'>here</a>!", "From: DulyNotedRPI@gmail.com");
				
				// Add to the Members Logs
				mysql_query("INSERT INTO logs (memberid, action, ip) VALUES ('$memberid', '$action', '$ip')");
				
				?>
				
				<h1>Your New Password</h1>
				<p class="centered">We've just sent you an email with your new password. Please <a href="members/login.php">login</a> using your new password so you can change this password to something you will remember.</p>
				
				<?php } ?>
				
			</div>
			
			<!-- Bottom Navigation Tray -->
			<?php echo $canvas['tray'] . "\r"; ?>
			
			<!-- Footnotes -->
			<?php include "footnotes.php"; ?>
			
		</div>
	</body>
	
</html>
<?php /* Close connection to the MySQL Database */ mysql_close($connection); ?>
