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
		<title>Update Album - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
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
				$id = cleanse ($_SESSION['albumid']);
				$album = cleanse ($_POST['album']);
				$semester = cleanse ($_POST['season']) . ' ' . cleanse ($_POST['year']);
				$tracknum = cleanse ($_POST['tracknum']);
				
				// Store Album Tracks
				$index = 1;
				for ($track = 1; $track <= $tracknum; $track++) {
					$tracks[$index][0] = cleanse ($_POST['track' . $track]);
					$tracks[$index][1] = cleanse ($_POST['artist' . $track]);
					$tracks[$index][2] = cleanse ($_POST['solo' . $track]);
					if ($tracks[$index][0] != '' && $tracks[$index][1] != '') $index++;
					else array_pop($tracks);
				}
				$tracks = serialize($tracks);
				
				// Convert Semester to appropriate timedate
				$date = semesterDate ($semester);
				
				// Validate Posted Variables
				validate(array($album, $semester, $memberid));
				
				// Update Album on the MySQL Database
				mysql_query("UPDATE discography SET album='$album', tracks='$tracks', semester='$semester', date='$date', deleted=0 WHERE id='$id'");
				
				// Setup Member Log
				$action = 'updated album <a href="discography.php?id=' . $id . '">' . $album . '</a>';
				
				// Add to the Members Logs
				mysql_query("INSERT INTO logs (memberid, action, ip) VALUES ('$memberid', '$action', '$ip')");
				
				// Setup Post
				$title = $album;
				$tracks = unserialize($tracks);
				$row = mysql_query("SELECT atomid FROM discography WHERE id='$id'");
				$atomid = $row['atomid'];
				$content = 'We just released our latest album <a href="http://dulynoted.union.rpi.edu/discography.php?id=' . $id . '">' . $album .'</a>. Tracks from this album include: ';
				for ($x = 1; $x < count($tracks); $x++) $content .= '<em>' . $tracks[$x][0] . '</em> by ' . $tracks[$x][1] . ', ';
				$content .= 'and <em>' . $tracks[$x][0] . '</em> by ' . $tracks[$x][1] . '.';
				$updated = date("c");
				
				// Update Post on the MySQL Database
				mysql_query("UPDATE posts SET title='$title', content='$content' WHERE id='$atomid'");
				
				// Setup Member Log
				$action = 'updated post <a href="news_feed.php?id=' . $row['atomid'] . '">' . $title . '</a>';
				
				// Add to the Members Logs
				mysql_query("INSERT INTO logs (memberid, action, ip) VALUES ('$memberid', '$action', '$ip')");
				
				?>
				
				<h1><a href="discography.php?id=<?php echo $id; ?>"><?php echo $album; ?></a> Updated</h1>
				<p class="centered">
					<a href="discography.php?id=<?php echo $id; ?>"><?php echo $album; ?></a> has been successfully updated.<br />
					<a href="members/albums.php">Go Back</a>
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
