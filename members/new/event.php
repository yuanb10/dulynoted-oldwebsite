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
		<title>Create a New Event - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
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
						$("#date2").datepicker({showOn: 'button', buttonImage: 'members/.images/calendar.gif', buttonImageOnly: true, dateFormat: 'yy-mm-dd'});
					});
				</script>
				
				<?php /* Display Members Area Navigation Menu */ include "menu.php"; ?>
				<h1>New Event</h1>
				<form name="editbox" method="post" action="members/actions/new/event.php">
					<div>
						<div class="input_box_thin">
							<label for="summary">Event</label><br />
							<input type="text" name="summary" id="summary" />
						</div>
						<div class="input_box_thin">
							<label for="location">Location</label><br />
							<input type="text" name="location" id="location" />
						</div>
						<div class="input_box_thin">
							<label for="cost">Cost</label><br />
							<input type="text" name="cost" id="cost" />
						</div>
						<div class="input_box_thin">
							<label for="facebook" style="font-size:75%"><a href="http://www.facebook.com/events.php">Facebook Event ID</a></label><br />
							<input type="text" name="facebook" id="facebook" />
						</div>
						<div class="input_box" style="clear:both">
							<label for="date">Start</label><br />
							<input type="text" name="date" id="date" /><br />
							<select name="hour">
								<?php for ($x = 1; $x <= 12; $x++) { ?><option value="<?php echo substr('00010203040506070809101112', 2*$x, 2); ?>"><?php echo substr("00010203040506070809101112", 2*$x, 2); ?></option>
								<?php } echo "\r"; ?>
							</select>
							<select name="minute">
								<?php for ($x = 0; $x <= 3; $x++) { ?><option value="<?php echo substr('00153045', 2*$x, 2); ?>"><?php echo substr("00153045", 2*$x, 2); ?></option>
								<?php } echo "\r"; ?>
							</select>
							<select name="ampm">
								<option value="AM">AM</option>
								<option value="PM">PM</option>
							</select>
						</div>
						<div class="input_box">
							<label for="date2">End</label><br />
							<input type="text" name="date2" id="date2" /><br />
							<select name="hour2">
								<?php for ($x = 1; $x <= 12; $x++) { ?><option value="<?php echo substr('00010203040506070809101112', 2*$x, 2); ?>"><?php echo substr("00010203040506070809101112", 2*$x, 2); ?></option>
								<?php } echo "\r"; ?>
							</select>
							<select name="minute2">
								<?php for ($x = 0; $x <= 3; $x++) { ?><option value="<?php echo substr('00153045', 2*$x, 2); ?>"><?php echo substr("00153045", 2*$x, 2); ?></option>
								<?php } echo "\r"; ?>
							</select>
							<select name="ampm2">
								<option value="AM">AM</option>
								<option value="PM">PM</option>
							</select>
						</div>
						<span style="display: block; clear: both;"><input type="submit" name="submit" value="Create Event" style="display:block;margin:0 auto;" /></span>
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
