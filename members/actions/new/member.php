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
		<title>New Member - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
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
				$firstname = cleanse ($_POST['firstname']);
				$lastname = cleanse ($_POST['lastname']);
				$email = cleanse ($_POST['email']);
				$class = date("Y") + 4;
				$position = "Member";
				$studying = "On Campus";
				
				// Validate Posted Variables
				validate(array($username, $firstname, $lastname, $email, $memberid));
				
				// Create Random 16-Character Alphanumeric Password
				$alphanumeric = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
				for($repeat = 0; $repeat < 16; $repeat++) $password .= substr($alphanumeric, rand(0,36), 1);
				
				// Create Random Password Salt
				$salt = substr(md5(uniqid(rand(), true)), 0, rand() % 22 + 10);
				
				// Encrypt Password
				$password_encrypted = $salt . hash('sha256', $salt . $password);
				
				// Find Member with the same username if it exists
				$member = mysql_fetch_array(mysql_query("SELECT username FROM members WHERE username='$username'"));
				
				// Return Error if username exists already
				if ($username == $member['username']) header ("location: http://dulynoted.union.rpi.edu/members/includes/error.php?error=This username already exists!");
				else {
				
				// Update Member on the MySQL Database
				if (!$q = mysql_query("INSERT INTO members (username, password, firstname, lastname, email, class, position, studying, salt) VALUES ('$username', '$password_encrypted', '$firstname', '$lastname', '$email', '$class', '$position', '$studying', '$salt')"))
				{
					die ("MYSQL ERROR: ".mysql_error());
				}
				
				
				// Send Email to Member with Username and Password
				mail($email, "Your New Duly Noted Account", "Username: $username, Password: $password. Login <a href='http://dulynoted.union.rpi.edu/members/login.php'>here</a>.", "From: DulyNotedRPI@gmail.com");
				
				// Create default profile picture for member
				$picture = '/home/dulynote/public_html/images/members/member' . $id . '.jpg';
				copy('/home/dulynote/public_html/images/members/unknown.jpg', $picture);
				
				// Search for and fetch Member from the MySQL Database
				$member = mysql_fetch_array(mysql_query("SELECT id FROM members WHERE username = '$username'") or trigger_error("MYSQL ERROR: ".mysql_error(),E_USER_ERROR)) or trigger_error("MYSQL ERROR: ".mysql_error(), E_USER_ERROR);
				
				// Setup Member Log
				$action = 'created member <a href="members.php?id=' . $member['id'] . '">' . $_SESSION['username'] . '</a>';
				
				// Add to the Members Logs
				mysql_query("INSERT INTO logs (memberid, action, ip) VALUES ('$memberid', '$action', '$ip')");
				
				?>
				
				<h1><a href="members.php?id=<?php echo $member['id']; ?>"><?php echo $firstname . ' ' . $lastname; ?></a> Created</h1>
				<p class="centered">
					<a href="members.php?id=<?php echo $member['id']; ?>"><?php echo $firstname . ' ' . $lastname; ?></a> has been successfully created.<br />
					<a href="members/new/member.php">Go Back</a>
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
