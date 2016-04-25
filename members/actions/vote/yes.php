<?php /* Enable GZIP Compression */ include "/home/dulynote/public_html/members/includes/gzip_compress.php"; ?>
<?php /* Connect to MySQL Database on the PHP server */ session_start(); include "connect.php"; ?>
<?php /* Get Canvas Elements */ $canvas = mysql_fetch_array(mysql_query("SELECT logo, navbar, tray FROM canvas WHERE id=1")); ?>
<?php /* Authorize Member and Verify if Logged-In */ include "auth.php"; ?>
<?php /* Form Validation */ include "validate.php"; ?>
<?php /* Clean Bad Characters in Variables */ include "cleanse_variable.php"; ?>
<?php /* Get IP Address of Logged-In Member */ include "ip_address.php"; ?>
<?php /* Get Member ID of Logged-In Member */ include "memberid.php"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	
	<head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="robots" content="noindex, nofollow" />
		<title>Vote Yes - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
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
				$id = cleanse ($_REQUEST['id']);
				
				// Validate Posted Variables
				validate(array($id, $memberid));
				
				// Find Post to Delete
				$vote = mysql_fetch_array(mysql_query("SELECT title, yes, no FROM votes WHERE id='$id'"));
				
				// Convert Votes to Arrays
				$supporters = explode(" ", $vote['yes']);
				$opposers = explode(" ", $vote['no']);
				
				// Return Error if Member already voted
				if (array_search($memberid, $supporters) != '') header ("location: http://dulynoted.union.rpi.edu/members/includes/error.php?error=You have already voted for this song!");
				else if (array_search($memberid, $opposers) != '') header ("location: http://dulynoted.union.rpi.edu/members/includes/error.php?error=You have already voted against this song!");
				else {
				
				// Add Vote in Support for song
				if (!(array_search($memberid, $supporters) || array_search($memberid, $opposers))) $yes = $vote['yes'] . ' ' . $memberid;
				else $yes = $vote['yes'];
				
				// Calculate Vote Score
				$score = count(explode(" ", $yes)) - count($opposers);
				
				// Setup Member Log
				$action = 'voted for <a href="members/votes.php?id=' . $id . '">' . $vote['title'] . '</a>';
				
				// Update Post on the MySQL Database
				mysql_query("UPDATE votes SET yes='$yes', score='$score' WHERE id='$id'");
				
				// Add to the Members Logs
				mysql_query("INSERT INTO logs (memberid, action, ip) VALUES ('$memberid', '$action', '$ip')");
				
				?>
				
				<h1>Vote for <a href="members/votes.php?id=<?php echo $id; ?>"><?php echo $vote['title']; ?></a> Submitted</h1>
				<p class="centered">
					Your vote for <a href="members/votes.php?id=<?php echo $id; ?>"><?php echo $vote['title']; ?></a> has been successfully submitted.<br />
					<a href="members/actions/vote/undo.php?id=<?php echo $id; ?>">Undo</a> |
					<a href="members/votes.php">Go Back</a>
				</p>
				
				<?php } ?>
				
			</div>
			
			<!-- Bottom Navigation Tray -->
			<?php echo $canvas['tray'] . "\r"; ?>
			
			<!-- Footnotes -->
			<?php include "footnotes.php"; ?>
			
		</div>
	</body>
	
</html>
<?php /* Close connection to the MySQL Database */ mysql_close($connection); ?>
