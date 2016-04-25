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
		<title>Update Profile - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
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
				$id = cleanse ($_SESSION['userid']);
				$username = cleanse ($_POST['username']);
				$firstname = cleanse ($_POST['firstname']);
				$lastname = cleanse ($_POST['lastname']);
				$email = cleanse ($_POST['email']);
				$phone = cleanse ($_POST['areacode']) . cleanse ($_POST['prefix']) . cleanse ($_POST['line']);
				$provider = cleanse ($_POST['provider']);
				$class = cleanse ($_POST['class']);
				$rin = cleanse ($_POST['rin']);
				$shirtsize = cleanse ($_POST['shirtsize']);
				$voicepart = cleanse ($_POST['voicepart']);
				$position = cleanse ($_POST['position']);
				$studying = cleanse ($_POST['studying']);
				$bio = cleanse ($_POST['bio']);
				
				// Validate Posted Variables
				validate(array($id, $username, $memberid, $firstname, $lastname, $email, $phone, $provider, $class, $rin, $voicepart, $position, $studying, $bio));
				
				// Update Member on the MySQL Database
				mysql_query("UPDATE members SET firstname='$firstname', lastname='$lastname', email='$email', phone='$phone', provider='$provider', class='$class', rin='$rin', shirtsize='$shirtsize', voicepart='$voicepart', position='$position', studying='$studying', bio='$bio' WHERE id='$id'");
				
				// Setup Member Log
				$action = "updated his profile";
				
				// Add to the Members Logs
				mysql_query("INSERT INTO logs (memberid, action, ip) VALUES ('$memberid', '$action', '$ip')");
				
				?>
				
				<h1><a href="members.php?id=<?php echo $id; ?>">Your</a> Profile Updated</h1>
				<p class="centered">
					<a href="members.php?id=<?php echo $id; ?>">Your</a> profile has been successfully updated.<br />
					<a href="members/index.php">Go Back</a>
				</p>
				
			</div>
			
			<!-- Bottom Navigation Tray -->
			<?php echo $canvas['tray'] . "\r"; ?>
			
			<!-- Footnotes -->
			<?php include "footnotes.php"; ?>
			
		</div>
	</body>
	
</html>
<?php /* Close connection to the MySQL Database */ mysql_close($connection); ?>
