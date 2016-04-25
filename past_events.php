<?php /* Enable GZIP Compression */ include "/home/dulynote/public_html/members/includes/gzip_compress.php"; ?>
<?php /* Connect to MySQL Database on the PHP server */ session_start(); include "connect.php"; ?>
<?php /* Get Canvas Elements */ $canvas = mysql_fetch_array(mysql_query("SELECT logo, navbar, addthis, tray FROM canvas WHERE id=1")); ?>
<?php /* Fetch Page Description from MySQL Database */ $url = explode("/", $_SERVER["SCRIPT_NAME"]); $url = explode(".", $url[count($url)-1]); $webpage = mysql_fetch_array(mysql_query("SELECT title, item, heading, description, detail, content, note, searchable FROM pages WHERE page='$url[0]'")); if ($_REQUEST['id'] > 0) { eval($webpage['detail']); } else $description =  $webpage['description']; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	
	<head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="description" content="<?php /* Page Description */ echo $description; ?>" />
		<title><?php /* Fetch Page Title from MySQL Database */ if ($_REQUEST['id'] > 0) { eval($webpage['item']); echo " - "; } echo $webpage['title']; ?> - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
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
				
				<h1><?php echo $webpage['heading'];?></h1>
				<?php	echo $webpage['content'] . "\r"; ?>
				
				<div class="shaded_div">
					<?php
					
					// Include Extensions
					include "display_time.php";
					include "timestamper.php";
					
					// Obtain page # or item ID from URL
					$page = $_REQUEST['page'];
					$id = $_REQUEST['id'];
					if ($page < 1) $page = 1;
					if ($id < 1) $id = 0;
					
					// Set table size and scroll width
					list ($limit, $width) = array (25, 5);
					
					// Determine position of first (top) item on table
					$num_rows = mysql_num_rows(mysql_query("SELECT id FROM " . $webpage['searchable']));
					if ($page > $num_rows / $limit) $page = ceil($num_rows / $limit);
					$start = $limit * ($page - 1);
					$width += (floor($width / 2) == $width / 2);
					
					if ($id > 0) {
						
						// Search for and fetch Event from the MySQL Database
						$row = mysql_fetch_array(mysql_query("SELECT archived, canceled FROM events WHERE id='$id'"));
						
						// Redirect Upcoming Event to non-archives page
						if (!$row['archived']) header('location: events.php?id=' . $id);
						
						// Return Error if Event canceled
						if ($row['canceled'] || count($row) == 1) header ('location: 404.shtml');
					
					}
					
					// Search for and fetch Event(s) from the MySQL Database
					if ($id > 0) $result = mysql_query("SELECT id, dtstart, dtend, summary, location, cost, facebook FROM " . $webpage['searchable'] . " && id='$id'");
					else $result = mysql_query("SELECT id, dtstart, dtend, summary, location, cost, facebook FROM " . $webpage['searchable'] . " ORDER BY dtstart DESC LIMIT $start,$limit");
					
					// Insert Event data into table
					if ($num_rows > 0 || $id > 0) {
						while ($row = mysql_fetch_array($result))
			{ ?><div>
						<a href="events.php?id=<?php echo $row['id']; ?>"><?php /* event */ echo $row['summary']; ?></a>
						on <?php /* date */ echo date('D F d, Y', strtotime($row['dtstart'])) . "\r"; ?>
						at <em><?php /* location */ echo $row['location']; ?></em><br />
						<?php /* start */ echo displayTime(substr($row['dtstart'], 11, 5)) . "\r"; ?>
						to <?php /* end */ echo displayTime(substr($row['dtend'], 11, 5)) . " /\r"; ?>
						<?php /* cost */ echo $row['cost'] . "\r"; ?><?php if ($row['facebook'] != '') { ?> / <a href="http://www.facebook.com/event.php?eid=<?php echo $row['facebook']; ?>">RSVP on Facebook!</a><?php } ?>
						<span style="float:right; font-size:90%"><?php echo timestamper ($row['dtend']); ?></span>
					</div>
					<?php	}
						echo "\r";
					} else echo "<div>Oops! It looks like this event does not exist...</div>\r";
					?>
				</div>
				<?php /* Page Navigation */ include "pages.php"; ?>
				
				<div class="note"><?php echo $webpage['note']; ?></div>
				
				<!-- AddThis -->
				<?php echo $canvas['addthis'] . "\r"; ?>
				
			</div>
			
			<!-- Bottom Navigation Tray -->
			<?php echo $canvas['tray'] . "\r"; ?>
			
			<!-- Footnotes -->
			<?php include "footnotes.php"; ?>
		
		</div>
	</body>
	
</html>
<?php /* Close connection to the MySQL Database */ mysql_close($connection); ?>
