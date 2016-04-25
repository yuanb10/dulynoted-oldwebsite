<?php /* Enable GZIP Compression */ include "/home/dulynote/public_html/members/includes/gzip_compress.php"; ?>
<?php /* Connect to MySQL Database on the PHP server */ session_start(); include "connect.php"; ?>
<?php /* Get Canvas Elements */ $canvas = mysql_fetch_array(mysql_query("SELECT logo, navbar, tray FROM canvas WHERE id=1")); ?>
<?php /* Authorize Member and Verify if Logged-In */ include "auth.php"; ?>
<?php /* Authorize Member and Verify if Student */ include "student_auth.php"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	
	<head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="robots" content="noindex, nofollow" />
		<title>Phonebook - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
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
				<h1>The Phonebook</h1>
				<div class="shaded_div">
					<?php
					
					// Declare variables for data manipulation
					$provider_search = mysql_query("SELECT name, email FROM txt_providers ORDER BY name");
					while ($providers = mysql_fetch_array($provider_search)) $provider[$providers['name']] = $providers['email'];
					
					// Search for and fetch Member(s) from the MySQL Database
					$result = mysql_query("SELECT id, username, firstname, lastname, email, phone, provider FROM members WHERE position!='Alumnus' && position!='Inactive' ORDER BY lastname");
					
					// Insert Member data into table
					while($row = mysql_fetch_array($result))
			{ ?><div style="min-height:<?php $size = getimagesize('/home/dulynote/public_html/images/members/member' . $row['id'] . '.jpg'); echo $size[1] / 2; ?>px; padding-right:10px;">
						<span style="float:right;">
							<a href="mailto:<?php echo $row['email']; ?>"><?php echo $row['email']; ?></a> /
							<a href="mailto:<?php echo $row['phone'] . '@' . $provider[$row[provider]]; ?>">(<?php echo substr($row['phone'], 0, 3) . ") " . substr($row['phone'], 3, 3) . "-" . substr($row['phone'], 6, 4); ?></a>
						</span>
						<img src="images/image.php?src=members/member<?php echo $row['id']; ?>.jpg" style="float:left; width:50px; margin-right:10px;" />
						<a href="members.php?id=<?php echo $row['id']; ?>"><?php echo $row['firstname'] . " " . $row['lastname']; ?></a><br />
					</div>
					<?php } echo "\r"; ?>
				</div>
				<p class="centered">Email <a href="mailto:notes@union.rpi.edu">notes@union.rpi.edu</a> to contact all members at once!</p>
				
			</div>
			
			<!-- Bottom Navigation Tray -->
			<?php echo $canvas['tray'] . "\r"; ?>
			
			<!-- Footnotes -->
			<?php include "footnotes.php"; ?>
			
		</div>
	</body>
	
</html>
<?php /* Close connection to the MySQL Database */ mysql_close($connection); ?>
