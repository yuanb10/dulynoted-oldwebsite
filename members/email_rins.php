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
		<title>Email RINs to the Union Admin - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
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
				<h1>Email Members and <acronym title="Rensselaer Identification Number">RIN</acronym>s to the Union Admin</h1>
				<form name="editbox" method="post" action="members/actions/email/rins.php">
					<div>
						<label for="sendto">Send To</label>
						<br /><input type="text" name="sendto" id="sendto" value="raue@rpi.edu" />
						<br /><label for="subject">Subject</label>
						<br /><input type="text" name="subject" id="subject" value="Duly Noted RINs" />
						<br /><label for="body">Body</label><br />
						<textarea name="body" id="body" style="width:764px;height:100px;">Hi Erika,
All current members of Duly Noted are listed below along with their RINs.

Officers:
<?php
							
							// Search for and fetch Member(s) from the MySQL Database
							$member_search = mysql_query("SELECT firstname, lastname, position, rin FROM members WHERE position!='Alumnus' && position!='Member' && position!='Inactive' ORDER BY lastname ASC");
							
							// Insert Member data into form
							while($members = mysql_fetch_array($member_search)) echo $members['firstname'] . " " . $members['lastname'] . ", " . $members['rin'] . "\n";
							
							?>

Regular Members:
<?php
							
							// Search for and fetch Member(s) from the MySQL Database
							$member_search = mysql_query("SELECT firstname, lastname, position, rin FROM members WHERE position='Member' && studying='On Campus' ORDER BY lastname ASC");
							
							// Insert Member data into form
							while($members = mysql_fetch_array($member_search)) echo $members['firstname'] . " " . $members['lastname'] . ", " . $members['rin'] . "\n";
							
					?>

--
Duly Noted A Cappella
The Rensselaer Union
110 8th Street
Troy, New York 12180-3599
http://dulynoted.union.rpi.edu/
DulyNotedRPI@gmail.com</textarea>
						<br /><input type="submit" name="submit" value="Send Email" style="display:block;margin:0 auto;" />
					</div>
				</form>
				
			</div>
			
			<!-- Bottom Navigation Tray -->
			<?php echo $canvas['tray'] . "\r"; ?>
			
			<!-- Footnotes -->
			<?php include "footnotes.php"; ?>
			
		</div>
	</body>
	
</html>
<?php /* Close connection to the MySQL Database */ mysql_close($connection); ?>
