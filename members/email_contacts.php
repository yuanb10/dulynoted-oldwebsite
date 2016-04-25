<?php /* Enable GZIP Compression */ include "/home/dulynote/public_html/members/includes/gzip_compress.php"; ?>
<?php /* Connect to MySQL Database on the PHP server */ session_start(); include "connect.php"; ?>
<?php /* Get Canvas Elements */ $canvas = mysql_fetch_array(mysql_query("SELECT logo, navbar, tray FROM canvas WHERE id=1")); ?>
<?php /* Authorize Member and Verify if Logged-In */ include "auth.php"; ?>
<?php /* Authorize Member and Verify if Officer */ include "officer_auth.php"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	
	<head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="robots" content="noindex, nofollow" />
		<title>Email the Contact List - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
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
				<h1>Send Email to the Contact List</h1>
				<span style="font-weight: bold">Send To</span>
				<form name="editbox" method="post" action="members/actions/email/contacts.php">
					<div>
						<input type="button" onclick="checkAll()" value="Check All" />
						<input type="button" onclick="uncheckAll()" value="Uncheck All" /><br />
						<div class="shaded_div" style="height: 200px; overflow: auto;">
							<?php
							
							// Search for Contacts that haven't been deleted
							$result = mysql_query("SELECT id, name, email, school FROM contacts WHERE deleted=0 ORDER BY name");
							
							// Fetch Contacts from the MySQL Database
							while ($row = mysql_fetch_array($result))
					{ ?><div>
								<input type="checkbox" name="contacts[]" id="contact<?php echo $row['id']; ?>" value="<?php echo $row['email']; ?>" />
								<span style="float:right;"><?php echo $row['school']; ?></span>
								<label for="contact<?php echo $row['id']; ?>"><?php echo $row['name']; ?></label><br />
							</div>
							<?php } echo "\r"; ?>
						</div>
						<br /><label for="subject">Subject</label>
						<br /><input type="text" name="subject" id="subject" class="required" />
						<br /><label for="body">Body</label>
						<br /><textarea name="body" id="body" style="width:764px;height:100px;">

--
Duly Noted A Cappella
The Rensselaer Union
110 8th Street
Troy, New York 12180-3599
http://dulynoted.union.rpi.edu/
DulyNotedRPI@gmail.com</textarea>
						<br /><input type="submit" name="submit" value="Send Email to Contact List" style="display:block;margin:0 auto;" />
					</div>
				</form>
				
				<p class="centered">Import the <a href="members/includes/vCard.php">Contact List</a> into your email client.</p>
				
			</div>
			
			<!-- Bottom Navigation Tray -->
			<?php echo $canvas['tray'] . "\r"; ?>
			
			<!-- Footnotes -->
			<?php include "footnotes.php"; ?>
			
		</div>
	</body>
	
</html>
<?php /* Close connection to the MySQL Database */ mysql_close($connection); ?>
