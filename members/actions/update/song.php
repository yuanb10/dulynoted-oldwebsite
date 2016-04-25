<?php /* Enable GZIP Compression */ include "/home/dulynote/public_html/members/includes/gzip_compress.php"; ?>
<?php /* Connect to MySQL Database on the PHP server */ session_start(); include "connect.php"; ?>
<?php /* Get Canvas Elements */ $canvas = mysql_fetch_array(mysql_query("SELECT logo, navbar, tray FROM canvas WHERE id=1")); ?>
<?php /* Authorize Member and Verify if Logged-In */ include "auth.php"; ?>
<?php /* Authorize Member and Verify if Officer */ include "officer_auth.php"; ?>
<?php /* Form Validation */ include "validate.php"; ?>
<?php /* Convert Semester to an appropriate Date */ include "semester_date.php"; ?>
<?php /* Clean Bad Characters in Variables */ include "cleanse_variable.php"; ?>
<?php /* Get IP Address of Logged-In Member */ include "ip_address.php"; ?>
<?php /* Get Member ID of Logged-In Member */ include "memberid.php"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	
	<head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="robots" content="noindex, nofollow" />
		<title>Update Song - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
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
				$id = cleanse ($_SESSION['songid']);
				$artist = cleanse ($_POST['artist']);
				$title = cleanse ($_POST['title']);
				$semester = cleanse ($_POST['season']) . ' ' . cleanse ($_POST['year']);
				$youtube = cleanse ($_POST['youtube']);
				$tonic = cleanse ($_POST['tonic']) . cleanse ($_POST['sharp']) . ' ' . cleanse ($_POST['major']);
				$solos = explode(" ", cleanse ($_POST['solo']));
				$date = semesterDate ($semester);
				$soloid = $solos[2];
				
				// Setup Member Log
				$action = 'updated song <a href="repertoire.php?id=' . $id . '">' . $title . '</a>';
				
				// Validate Posted Variables
				validate(array($id, $artist, $title, $semester, $date, $youtube, $memberid));
				
				// Search for and fetch Song from the MySQL Database
				$song = mysql_fetch_array(mysql_query("SELECT atomid FROM repertoire WHERE id='$id'"));
				$atomid = $song['atomid'];
				
				// Update Song on the MySQL Database
				mysql_query("UPDATE repertoire SET artist='$artist', title='$title', semester='$semester', date='$date', soloid='$soloid', youtube='$youtube', tonic='$tonic' WHERE id='$id'");
				
				// Add to the Members Logs
				mysql_query("INSERT INTO logs (memberid, action, ip) VALUES ('$memberid', '$action', '$ip')");
				
				// Setup Post
				$content = "<em>New Repertoire:</em> <a href=\"http://dulynoted.union.rpi.edu/repertoire.php?id=" . $song['id'] . "\">" . $title . "</a> by <a href=\"http://www.last.fm/music/" . str_ireplace(" ", "+", $artist) . "\">" . $artist . "</a>.";
				if ($youtube != '') $content .= " <a href=\"" . $youtube . "\">Watch a music video of this song on YouTube!</a>";
				
				// Update Post on the MySQL Database
				mysql_query("UPDATE posts SET title='$title', content='$content' WHERE id='$atomid'");
				
				// Setup Member Log
				$action = 'updated post <a href="news_feed.php?id=' . $atomid . '">' . $title . '</a>';
				
				// Add to the Members Logs
				mysql_query("INSERT INTO logs (memberid, action, ip) VALUES ('$memberid', '$action', '$ip')");	
				
				?>
				
				<h1><a href="repertoire.php?id=<?php echo $id; ?>"><?php echo $title; ?></a> Updated</h1>
				<p class="centered">
					<a href="repertoire.php?id=<?php echo $id; ?>"><?php echo $title; ?></a> has been successfully updated.<br />
					<a href="members/songs.php">Go Back</a>
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