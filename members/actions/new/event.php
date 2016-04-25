<?php /* Enable GZIP Compression */ include "/home/dulynote/public_html/members/includes/gzip_compress.php"; ?>
<?php /* Connect to MySQL Database on the PHP server */ session_start(); include "connect.php"; ?>
<?php /* Get Canvas Elements */ $canvas = mysql_fetch_array(mysql_query("SELECT logo, navbar, tray FROM canvas WHERE id=1")); ?>
<?php /* Authorize Member and Verify if Logged-In */ include "auth.php"; ?>
<?php /* Authorize Member and Verify if Officer */ include "officer_auth.php"; ?>
<?php /* Form Validation */ include "validate.php"; ?>
<?php /* Format Datetime to be Displayed On-Screen */ include "display_time.php"; ?>
<?php /* Format Datetime for the MySQL Database */ include "database_time.php"; ?>
<?php /* Clean Bad Characters in Variables */ include "cleanse_variable.php"; ?>
<?php /* Get IP Address of Logged-In Member */ include "ip_address.php"; ?>
<?php /* Get Member ID of Logged-In Member */ include "memberid.php"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	
	<head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="robots" content="noindex, nofollow" />
		<title>New Event - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
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
				$summary = cleanse ($_POST['summary']);
				$location = cleanse ($_POST['location']);
				$date = cleanse ($_POST['date']);
				$hour = cleanse ($_POST['hour']);
				$minute = cleanse ($_POST['minute']);
				$ampm = cleanse ($_POST['ampm']);
				$date2 = cleanse ($_POST['date2']);
				$hour2 = cleanse ($_POST['hour2']);
				$minute2 = cleanse ($_POST['minute2']);
				$ampm2 = cleanse ($_POST['ampm2']);
				$cost = cleanse ($_POST['cost']);
				$facebook = cleanse ($_POST['facebook']);
				$sequence = 1;
				$archived = 0;
				
				// Convert Event Times to Store in the MySQL Database
				if ($date2 == '') $date2 = $date;
				$time = databaseTime ($hour . ':' . $minute . ' ' . $ampm);
				$time2 = databaseTime ($hour2 . ':' . $minute2 . ' ' . $ampm2);
				$dtstart = $date . ' ' . $time;
				$dtend = $date2 . ' ' . $time2;
				
				// Find ID of new post
				$post = mysql_fetch_array(mysql_query("SELECT id FROM posts ORDER BY id DESC LIMIT 1"));
				$atomid = $post['id'] + 1;
				
				// Validate Posted Variables
				validate(array($summary, $dtstart, $location, $cost, $memberid, $atomid));
				
				// Update Event on the MySQL Database
				mysql_query("INSERT INTO events (summary, sequence, dtstart, dtend, location, cost, facebook, atomid, archived) VALUES ('$summary', '$sequence', '$dtstart', '$dtend', '$location', '$cost', '$facebook', '$atomid', '$archived')");
				
				// Search for and fetch Event from the MySQL Database
				$event = mysql_fetch_array(mysql_query("SELECT id FROM events ORDER BY id DESC LIMIT 1"));
				
				// Setup Member Log
				$action = 'created event <a href="events.php?id=' . $event['id'] . '">' . $summary . '</a>';
				
				// Add to the Members Logs
				mysql_query("INSERT INTO logs (memberid, action, ip) VALUES ('$memberid', '$action', '$ip')");
				
				// Setup Post
				$title = $summary;
				$content = '<em>Upcoming Event:</em> <a href="http://dulynoted.union.rpi.edu/events.php?id=' . $event['id'] . '">' . $summary .'</a> on ' . date('D F d, Y', strtotime($dtstart));
				if ($location != 'TBD' && $location != '') $content .= ' at ' . $location;
				if ($dtstart != $dtend) $content .= ' from ' . displayTime(substr($dtstart, 11, 5)) . ' to ' . displayTime(substr($dtend, 11, 5));
				else if ($dtstart == $dtend) $content .= ' starting at ' . displayTime(substr($dtstart, 11, 5));
				$content .= '.';
				if ($cost != 'Free' && $cost != 'TBD' && $cost != '') $content .= ' Tickets will be on sale for ' . $cost . '!';
				else if ($cost == 'Free') $content .= ' Admissions is free!';
				if ($facebook != '') $content .= ' <a href="http://www.facebook.com/event.php?eid=' . $facebook . '">RSVP for this event on Facebook!</a>';
				$updated = date("c");
				
				// Update Post on the MySQL Database
				mysql_query("INSERT INTO posts (title, updated, content) VALUES ('$title', '$updated', '$content')");
				
				// Setup Member Log
				$action = 'created post <a href="news_feed.php?id=' . $atomid . '">' . $title . '</a>';
				
				// Add to the Members Logs
				mysql_query("INSERT INTO logs (memberid, action, ip) VALUES ('$memberid', '$action', '$ip')");
				
				?>
				
				<h1><a href="events.php?id=<?php echo $event['id']; ?>"><?php echo $summary; ?></a> Created</h1>
				<p class="centered">
					<a href="events.php?id=<?php echo $event['id']; ?>"><?php echo $summary; ?></a> has been successfully created.<br />
					<a href="members/new/event.php">Go Back</a>
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
