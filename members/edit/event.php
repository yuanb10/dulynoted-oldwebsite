<?php /* Enable GZIP Compression */ include "/home/dulynote/public_html/members/includes/gzip_compress.php"; ?>
<?php /* Connect to MySQL Database on the PHP server */ session_start(); include "connect.php"; ?>
<?php /* Get Canvas Elements */ $canvas = mysql_fetch_array(mysql_query("SELECT logo, navbar, tray FROM canvas WHERE id=1")); ?>
<?php /* Authorize Member and Verify if Logged-In */ include "auth.php"; ?>
<?php /* Authorize Member and Verify if Officer */ include "officer_auth.php"; ?>
<?php /* Format Datetime to be Displayed On-Screen */ include "display_time.php"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	
	<head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="robots" content="noindex, nofollow" />
		<title>Edit an Event - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
		<base href="http://dulynoted.union.rpi.edu/" />
		<link rel="search" type="application/opensearchdescription+xml" title="Duly Noted" href="search.xml" />
		<link href="atom/index.php" type="application/atom+xml" rel="alternate" title="Duly Noted A Cappella Feed" />
		<link href="members/.jquery-ui.css" rel="stylesheet" type="text/css" />
		<link href="style.css" type="text/css" rel="stylesheet" />
		<script type="text/javascript" src=".jquery.js"></script>
		<script type="text/javascript" src="members/.jquery-ui.js"></script>
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
				
				<script type="text/javascript">
					// Use jQuery UI popup calendar for dates
					$(function() {
						$("#date").datepicker({showOn: 'button', buttonImage: 'members/.images/calendar.gif', buttonImageOnly: true, dateFormat: 'yy-mm-dd'});
						$("#date2").datepicker({showOn: 'button', buttonImage: 'members/.images/calendar.gif', buttonImageOnly: true, dateFormat: 'yy-mm-dd'});
					});
				</script>
				
				<?php /* Display Members Area Navigation Menu */ include "menu.php"; ?>
				<?php
					
					// Search for and fetch Event from the MySQL Database
					$id = $_REQUEST['id'];
					$_SESSION['eventid'] = $id;
					$row = mysql_fetch_array(mysql_query("SELECT dtstart, dtend, summary, location, cost, facebook FROM events WHERE id='$id' && archived=0 && canceled=0"));
					
					// Declare variables for data manipulation
					$hours = "00010203040506070809101112";
					$minutes = "000510152025303540455055";
					$start = displayTime(substr($row['dtstart'], 11, 5));
					$end = displayTime(substr($row['dtend'], 11, 5));
					
				?>
				<h1>Edit <em><a href="events.php?id=<?php echo $id; ?>"><?php echo $row['summary']; ?></a></em></h1>
				<form name="editbox" method="post" action="members/actions/update/event.php">
					<div>
						<div class="input_box_thin">
							<label for="summary">Event</label><br />
							<input type="text" name="summary" id="summary" maxlength="32" value="<?php echo $row['summary']; ?>" />
						</div>
						<div class="input_box_thin">
							<label for="location">Location</label><br />
							<input type="text" name="location" id="location" maxlength="256" value="<?php echo htmlspecialchars($row['location']); ?>" />
						</div>
						<div class="input_box_thin">
							<label for="cost">Cost</label><br />
							<input type="text" name="cost" id="cost" maxlength="10" value="<?php echo $row['cost']; ?>" />
						</div>
						<div class="input_box_thin">
							<label for="facebook" style="font-size:75%"><a href="http://www.facebook.com/events.php">Facebook Event ID</a></label><br />
							<input type="text" name="facebook" id="facebook" maxlength="16" value="<?php echo $row['facebook']; ?>" />
						</div>
						<div class="input_box" style="clear:both">
							<label for="date">Start</label><br />
							<input type="text" name="date" id="date" maxlength="10" value="<?php echo substr($row['dtstart'], 0, 10); ?>" />
							<select name="hour">
								<option maxlength="2" value="<?php echo substr($start, 0, 2); ?>"><?php echo substr($start, 0, 2); ?></option>
								<?php for ($x = 1; $x <= 12; $x++) if (substr($start, 0, 2) != $x) { ?><option maxlength="2" value="<?php echo substr($hours, 2*$x, 2); ?>"><?php echo substr($hours, 2*$x, 2); ?></option>
								<?php } echo "\r"; ?>
							</select>
							<select name="minute">
								<option maxlength="2" value="<?php echo substr($start, 3, 2); ?>"><?php echo substr($start, 3, 2); ?></option>
								<?php for ($x = 0; $x <= 11; $x++) if (substr($start, 3, 2) != 5*$x) { ?><option maxlength="2" value="<?php echo substr($minutes, 2*$x, 2); ?>"><?php echo substr($minutes, 2*$x, 2); ?></option>
								<?php } echo "\r"; ?>
							</select>
							<select name="ampm">
								<option maxlength="2" value="<?php echo substr($start, 6, 2); ?>"><?php echo substr($start, 6, 2); ?></option>
								<?php if (substr($start, 6, 2) != 'AM') { ?><option maxlength="2" value="AM">AM</option>
								<?php } else { ?><option maxlength="2" value="PM">PM</option>
								<?php } echo "\r"; ?>
							</select>
						</div>
						<div class="input_box">
							<label for="date2">End</label><br />
							<input type="text" name="date2" id="date2" maxlength="10" value="<?php echo substr($row['dtend'], 0, 10); ?>" />
							<select name="hour2">
								<option maxlength="2" value="<?php echo substr($end, 0, 2); ?>"><?php echo substr($end, 0, 2); ?></option>
								<?php for ($x = 1; $x <= 12; $x++) if (substr($end, 0, 2) != $x) { ?><option maxlength="2" value="<?php echo substr($hours, 2*$x, 2); ?>"><?php echo substr($hours, 2*$x, 2); ?></option>
								<?php } echo "\r"; ?>
							</select>
							<select name="minute2">
								<option maxlength="2" value="<?php echo substr($end, 3, 2); ?>"><?php echo substr($end, 3, 2); ?></option>
								<?php for ($x = 0; $x <= 11; $x++) if (substr($end, 3, 2) != 5*$x) { ?><option maxlength="2" value="<?php echo substr($minutes, 2*$x, 2); ?>"><?php echo substr($minutes, 2*$x, 2); ?></option>
								<?php } echo "\r"; ?>
							</select>
							<select name="ampm2">
								<option maxlength="2" value="<?php echo substr($end, 6, 2); ?>"><?php echo substr($end, 6, 2); ?></option>
								<?php if (substr($end, 6, 2) != 'AM') { ?><option maxlength="2" value="AM">AM</option>
								<?php } else { ?><option maxlength="2" value="PM">PM</option>
								<?php } echo "\r"; ?>
							</select>
						</div>
						<span style="display: block; clear: both;"><input type="submit" name="submit" value="Update Event" style="display:block;margin:0 auto;" /></span>
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
