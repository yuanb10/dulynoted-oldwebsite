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
		<title>Change Password - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
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
				$old = cleanse ($_POST['old_password']);
				$new = cleanse ($_POST['new_password']);
				$confirm = cleanse ($_POST['confirm']);
				
				// Validate Posted Variables
				validate(array($memberid, $old, $new, $confirm));
				
				// Determine who is changing their password
				$member = mysql_fetch_array(mysql_query("SELECT username, password, salt FROM members WHERE username='$username'"));
				$salt = substr(hash('sha256', uniqid(rand(), true)), 0, rand() % 22 + 10);
				$old_encrypted = $member['salt'] . hash('sha256', $member['salt'] . $old);
				$new_encrypted = $salt . hash('sha256', $salt . $new);
				
				// Return Error if password change failed
				if ($username != $member['username']) header ("location: http://dulynoted.union.rpi.edu/members/includes/error.php?error=You've entered an invalid username!");
				else if ($old_encrypted != $member['password']) header ("location: http://dulynoted.union.rpi.edu/members/includes/error.php?error=You've entered an invalid password!");
				else if ($new != $confirm) header ("location: http://dulynoted.union.rpi.edu/members/includes/error.php?error=You did not confirm your new password!");
				else {
				
				// Setup Member Log
				$action = 'changed his password';
				
				// Update Member on the MySQL Database
				mysql_query("UPDATE members SET password='$new_encrypted', salt='$salt' WHERE username='$username' and password='$old_encrypted'");
				
				// Add to the Members Logs
				mysql_query("INSERT INTO logs (memberid, action, ip) VALUES ('$memberid', '$action', '$ip')");
				
				?>
				
				<h1>Password Changed</h1>
				<p class="centered">
					Your password has been successfully changed.<br />
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
