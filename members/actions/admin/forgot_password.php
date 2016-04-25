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
		<title>Your Password Reset Keycode - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
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
				$email = cleanse ($_REQUEST['email']);
				
				// Determine who forgot their password
				$member = mysql_fetch_array(mysql_query("SELECT id, username, email, keycode, keystamp FROM members WHERE email='$email' OR username='$email'"));
				
				// Find Member Info and Keycode Data
				$email = $member['email'];
				$username = $member['username'];
				$memberid = $member['id'];
				$keycode = $member['keycode'];
				$keystamp = $member['keystamp'];
				
				// Validate Posted Variables
				validate(array($username, $email));
				
				// Return Error if username or email failed or if keycode hasn't expired
				if ($keystamp != '0000-00-00 00:00:00' && ((time() - strtotime($keystamp)) / 86400 < 24)) header ("location: http://dulynoted.union.rpi.edu/members/includes/error.php?error=Your keycode hasn't expired yet!");
				else {
				
				// Declare variables for data manipulation
				$alphanumeric = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
				$keystamp = date("Y-m-d H:i:s");
				
				// Setup Member Log
				$action = 'forgot his password';
				
				// Create random alphanumeric 32-character keycode
				for($repeat = 0; $repeat < 32; $repeat++) $keycode .= substr($alphanumeric, rand(0,36), 1);
				
				// Update Member Keycode on the MySQL Database
				mysql_query("UPDATE members SET keycode='$keycode', keystamp='$keystamp' WHERE email='$email'");
				
				// Send Email to Member with Keycode and Timestamp
				mail($email, "Your Password Reset Keycode for Duly Noted", "Username: $username, Keycode: $keycode, Timestamp: $keystamp. You have 24 hours to reset your password! Verify your keycode by clicking <a href='http://dulynoted.union.rpi.edu/members/actions/admin/reset_password.php?username=$username&keycode=$keycode'>here</a>.", "From: DulyNotedRPI@gmail.com");
				
				// Add to the Members Logs
				mysql_query("INSERT INTO logs (memberid, action, ip) VALUES ('$memberid', '$action', '$ip')");
				
				?>
				
				<h1>Your Password Reset Keycode</h1>
				<p class="centered">We've just sent you an email with your password reset keycode. You have 24 hours to verify your keycode and reset your password.</p>
				
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
