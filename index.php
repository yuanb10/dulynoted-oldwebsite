<?php /* Enable GZIP Compression */ include "/home/dulynote/public_html/members/includes/gzip_compress.php"; ?>
<?php /* Connect to MySQL Database on the PHP server */ session_start(); include "connect.php"; ?>
<?php /* Get Canvas Elements */ $canvas = mysql_fetch_array(mysql_query("SELECT logo, navbar, addthis, tray FROM canvas WHERE id=1")); ?>
<?php /* Fetch Page Description from MySQL Database */ $url = explode("/", $_SERVER["SCRIPT_NAME"]); $url = explode(".", $url[count($url)-1]); $webpage = mysql_fetch_array(mysql_query("SELECT title, item, heading, description, detail, content, note, searchable FROM pages WHERE page='$url[0]'")); if ($_REQUEST['id'] > 0) { eval($webpage['detail']); } else $description =  $webpage['description']; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	
	<head>
		
		<!-- Verify Webmastering Tools -->
		<meta name="google-site-verification" content="swrqPk_8aqNvRg9GNXc7-RNo80ji37UboLHQN3hYkfg" />
		<meta name="y_key" content="4796d0c87b30f40f" />
		<meta name="msvalidate.01" content="A78FF7EAE6F27C24C1D802A86CE0BF75" />
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="description" content="<?php /* Page Description */ echo $description; ?>" />
		<meta name="keywords" content="Duly Noted A Cappella, Rensselaer Polytechnic Institute, Troy, NY, DulyNotedRPI, Music, Musician, Entertainment, Booking, Collegiate A Cappella" />
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
			
			<div class="canvas_clear">
				
				<?php
				
				// Include Extensions
				include "archive_past_events.php";
				include "countdown.php";
				
				// Search for and fetch Post(s) and Event(s) from the MySQL Database
				$post_search = mysql_query("SELECT id, title, updated FROM posts WHERE deleted=0 ORDER BY updated DESC LIMIT 3");
				$event_exists = mysql_num_rows(mysql_query("SELECT id FROM events WHERE archived=0 && canceled=0 LIMIT 1"));
				$event = mysql_fetch_array(mysql_query("SELECT id, dtstart, dtend, summary FROM events WHERE archived=0 && canceled=0 ORDER BY dtstart LIMIT 1"));
				
				?>
				
				<!-- Main Content of Page -->
				<div class="canvas_left">
						
					<h1><?php echo $webpage['heading']; ?></h1>
					<?php echo $webpage['content'] . "\r"; ?>
					
					<div class="note"><?php echo $webpage['note']; ?></div>
					
					<!-- AddThis -->
					<?php echo $canvas['addthis'] . "\r"; ?>
					
				</div>
				
				
				<!-- Recent News & Upcoming Events -->
				<div class="canvas_right">
					
					<h2>Recent News</h2>
					<p style="text-align:center; margin-top:-5px;">
						<?php /* Display Latest 3 Posts */ while ($post = mysql_fetch_array($post_search)) { ?>
						<a href="news_feed.php?id=<?php echo $post['id']; ?>"><?php /* post title */ echo $post['title']; ?></a><br />
						<?php } ?>
					</p>
					<?php if ($event_exists > 0) { ?>
					<hr />
					<h3>Upcoming Events</h3>
					<p style="text-align:center; margin-top:-5px;">
						<?php /* time until event */ echo countdown ($event['dtstart'], $event['dtend']); ?>
						<br /><a href="events.php?id=<?php echo $event['id']; ?>"><?php /* event */ echo $event['summary']; ?></a>
					</p>
					<?php echo "\r"; } ?>
					<hr />
					<h4>Join the Mailing List</h4>
					<form method="post" action=".subscribe.php" style="margin-bottom:10px">
						<div class="centered">
							<label for="email" style="font-size:85%">Email Address</label><br />
							<input type="text" name="email" id="email" /><br />
							<label for="name" style="font-size:85%">Full Name</label><br />
							<input type="text" name="name" id="name" />
							<input type="submit" name="submit" value="Subscribe" />
						</div>
					</form>
					<hr />
					<p class="centered" style="font-size:125%; font-weight:bold;"><a href="members/index.php">Member Login</a></p>
				</div>
				
			</div>
			
			<!-- Bottom Navigation Tray -->
			<?php echo $canvas['tray'] . "\r"; ?>
			
			<!-- Footnotes -->
			<?php include "footnotes.php"; ?>
			
		</div>
	</body>
	
</html>
<?php /* Close connection to the MySQL Database */ mysql_close($connection); ?>
