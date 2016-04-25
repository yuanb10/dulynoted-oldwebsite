<?php /* Enable GZIP Compression */ include "/home/dulynote/public_html/members/includes/gzip_compress.php"; ?>
<?php /* Connect to MySQL Database on the PHP server */ session_start(); include "connect.php"; ?>
<?php /* Get Canvas Elements */ $canvas = mysql_fetch_array(mysql_query("SELECT logo, navbar, tray FROM canvas WHERE id=1")); ?>
<?php /* Authorize Member and Verify if Logged-In */ include "auth.php"; ?>
<?php /* Form Validation */ include "validate.php"; ?>
<?php /* Clean Bad Characters in Variables */ include "cleanse_variable.php"; ?>
<?php /* Get IP Address of Logged-In Member */ include "ip_address.php"; ?>
<?php /* Get Member ID of Logged-In Member */ include "memberid.php"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	
	<head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="robots" content="noindex, nofollow" />
		<title>Change Email - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
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
				
				<?php /* Display Members Area Navigation Menu */ include "menu.php"; ?>
				<?php
				
				// Store Posted Variables
				$username = cleanse ($_POST['username']);
				$old = cleanse ($_POST['old_email']);
				$new = cleanse ($_POST['new_email']);
				$password = cleanse ($_POST['password']);
				$confirm = cleanse ($_POST['confirm']);
				
				// Validate Posted Variables
				validate(array($username, $old, $new, $password, $confirm, $memberid));
				
				// Determine who is changing their email
				$member = mysql_fetch_array(mysql_query("SELECT username, password, salt FROM members WHERE username='$username'"));
				$password_encrypted = $member['salt'] . hash('sha256', $member['salt'] . $password);
				
				// Return Error if email change failed
				if ($username != $member['username']) header ("location: http://dulynoted.union.rpi.edu/members/includes/error.php?error=You've entered an invalid username!");
				else if ($password_encrypted != $member['password']) header ("location: http://dulynoted.union.rpi.edu/members/includes/error.php?error=You've entered an invalid password!");
				else if ($password != $confirm) header ("location: http://dulynoted.union.rpi.edu/members/includes/error.php?error=You did not confirm your password!");
				else {
				
				// Setup Member Log
				$action = 'changed his email address';
				
				// Update Member on the MySQL Database
				mysql_query("UPDATE members SET email='$new' WHERE username='$username' and password='$password_encrypted'");
				
				// Add to the Members Logs
				mysql_query("INSERT INTO logs (memberid, action, ip) VALUES ('$memberid', '$action', '$ip')");
				
				// Send Email to Member to Verify Email Change
				mail($old, 'Your Email has been Changed for Duly Noted', 'Your email address on the <a href="http://dulynoted.union.rpi.edu/members/index.php">Duly Noted Members Area</a> has been changed from ' . $old . ' to ' . $new, 'From: DulyNotedRPI@gmail.com');
				mail($new, 'Your Email has been Changed for Duly Noted', 'Your email address on the <a href="http://dulynoted.union.rpi.edu/members/index.php">Duly Noted Members Area</a> has been changed from ' . $old . ' to ' . $new, 'From: DulyNotedRPI@gmail.com');
				
				?>
				
				<h1>Email Change</h1>
				<p class="centered">
					Your email address has been successfully changed to <a href="mailto:<?php echo $new; ?>"><?php echo $new; ?></a>.<br />
					<a href="members/index.php">Go Back</a>
				</p>
				
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
