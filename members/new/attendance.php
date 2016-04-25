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
		<title>Create a New Attendance Sheet - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
		<base href="http://dulynoted.union.rpi.edu/" />
		<link rel="search" type="application/opensearchdescription+xml" title="Duly Noted" href="search.xml" />
		<link href="atom/index.php" type="application/atom+xml" rel="alternate" title="Duly Noted A Cappella Feed" />
		<link href="style.css" type="text/css" rel="stylesheet" />
		<link href="members/.jquery-ui.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src=".jquery.js"></script>
		<script type="text/javascript" src="script.js"></script>
		<script type="text/javascript" src="members/.jquery-ui.js"></script>
		
	</head>
	
	<body>
		<div class="container">
			
			<!-- Duly Noted Logo -->
			<?php echo $canvas['logo'] . "\r"; ?>
			
			<!-- Navigation Menu -->
			<?php echo $canvas['navbar'] . "\r"; ?>
			
			<!-- Main Content of Page -->
			<div class="canvas">
			
			<script type="text/javascript">
				// Use jQuery UI popup calendar for dates
				$(function() {
					$("#date").datepicker({showOn: 'button', buttonImage: 'members/.images/calendar.gif', buttonImageOnly: true, dateFormat: 'yy-mm-dd'});
				});
			</script>
				
				<?php /* Display Members Area Navigation Menu */ include "menu.php"; ?>
				<h1>New Attendance Sheet</h1>
				<form name="editbox" method="post" action="members/actions/new/attendance.php">
					<div>
						<input type="text" name="date" id="date" value="<?php echo date('Y-m-d'); ?>" />
						<div class="shaded_div">
							<?php
							
							// Search for Contacts that haven't been deleted
							$result = mysql_query("SELECT id, firstname, lastname FROM members WHERE position!='Alumnus' && position!='Inactive' && studying='On Campus' ORDER BY lastname");
							
							// Fetch Contacts from the MySQL Database
							while ($row = mysql_fetch_array($result))
					{ ?><div>
								<label for="member<?php echo $row['id']; ?>"><a href="members.php?id=<?php echo $row['id']; ?>"><?php echo $row['firstname'] . " " . $row['lastname']; ?></a></label>
								<span style="float:right">
									<input type="radio" name="member<?php echo $row['id']; ?>" id="member<?php echo $row['id']; ?>" value="on time" /> On Time
									<input type="radio" name="member<?php echo $row['id']; ?>" id="member<?php echo $row['id']; ?>" value="late" /> Late
									<input type="radio" name="member<?php echo $row['id']; ?>" id="member<?php echo $row['id']; ?>" value="absent" /> Absent
									<input type="radio" name="member<?php echo $row['id']; ?>" id="member<?php echo $row['id']; ?>" value="excused" /> Excused
								</span>
							</div>
							<?php } echo "\r"; ?>
						</div>
						<br />
						<input type="submit" name="submit" value="Submit Attendance" style="display:block;margin:0 auto;" />
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
