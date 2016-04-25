<?php /* Enable GZIP Compression */ include "/home/dulynote/public_html/members/includes/gzip_compress.php"; ?>
<?php /* Connect to MySQL Database on the PHP server */ session_start(); include "connect.php"; ?>
<?php /* Get Canvas Elements */ $canvas = mysql_fetch_array(mysql_query("SELECT logo, navbar, tray FROM canvas WHERE id=1")); ?>
<?php /* Authorize Member and Verify if Logged-In */ include "auth.php"; ?>
<?php /* Authorize Member and Verify if Officer */ include "officer_auth.php"; ?>
<?php /* Form Validation */ include "validate.php"; ?>
<?php /* Clean Bad Characters in Variables */ include "cleanse_variable.php"; ?>
<?php /* Get IP Address of Logged-In Member */ include "ip_address.php"; ?>
<?php /* Get Member ID of Logged-In Member */ include "memberid.php"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	
	<head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="robots" content="noindex, nofollow" />
		<title>Send Text Message - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
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
				$txt = stripslashes(trim($_POST['txt'])) . "\n\n" . 'Reply 5182850732';
				
				// Setup Member Log
				$action = 'sent a group txt message';
				
				// Validate Posted Variables
				validate(array($txt, $memberid));
				
				// Get email domains for all txt providers in the MySQL Database
				$provider_search = mysql_query("SELECT name, email FROM txt_providers ORDER BY id");
				while($providers = mysql_fetch_array($provider_search)) $provider[$providers['name']] = $providers['email'];
				
				// Get email address for sending txt to all current members
				$emails[$index = 0] = 0;
				$member_search = mysql_query("SELECT phone, provider FROM members WHERE position!='Alumnus' && position!='Inactive' && studying='On Campus' ORDER BY lastname");
				while($members = mysql_fetch_array($member_search)) $emails[$index++] = $members['phone'] . '@' . $provider[$members['provider']];
				$recipients = implode(",",$emails);
				
				// Send TXT Message to all Current Members
				mail($recipients, " ", $txt, "From: DulyNotedRPI@gmail.com\r\nBCC: DulyNotedRPI@gmail.com");
				
				// Add to the Members Logs
				mysql_query("INSERT INTO logs (memberid, action, ip) VALUES ('$memberid', '$action', '$ip')");
				
				?>
				
				<h1>Text Message Sent</h1>
				<p class="centered">
					Your text message has been successfully sent to all the currently active on-campus members of the group.<br />
					<a href="members/send_txt.php">Go Back</a>
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