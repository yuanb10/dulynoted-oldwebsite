<?php /* Enable GZIP Compression */ include "/home/dulynote/public_html/members/includes/gzip_compress.php"; ?>
<?php /* Connect to MySQL Database on the PHP server */ session_start(); include "connect.php"; ?>
<?php /* Get Canvas Elements */ $canvas = mysql_fetch_array(mysql_query("SELECT logo, navbar, tray FROM canvas WHERE id=1")); ?>
<?php /* Authorize Member and Verify if Logged-In */ include "auth.php"; ?>
<?php /* Authorize Member and Verify if Student */ include "student_auth.php"; ?>
<?php /* Store Members Full Names into Array */ include "soloists.php"; ?>
<?php /* Store Members Positions into Array */ include "positions.php"; ?>
<?php /* Get Member ID of Logged-In Member */ include "memberid.php"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	
	<head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="robots" content="noindex, nofollow" />
		<title>Songs to Vote For - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
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
				<h1>Songs for Voting</h1>
				<div class="shaded_div">
					<?php
					
					// Obtain page # or item ID from URL
					$page = $_REQUEST['page'];
					$id = $_REQUEST['id'];
					if ($page < 1) $page = 1;
					if ($id < 1) $id = 0;
					
					// Set table size and scroll width
					list ($limit, $width) = array (25, 7);
					
					// Determine position of first (top) item on table
					$num_rows = mysql_num_rows(mysql_query("SELECT id FROM votes"));
					if ($page > $num_rows / $limit) $page = ceil($num_rows / $limit);
					$start = $limit * ($page - 1);
					$width += (floor($width / 2) == $width / 2);
					
					// Search for and fetch Vote(s) from the MySQL Database
					if ($id > 0) $result = mysql_query("SELECT id, memberid, artist, title, youtube, yes, no FROM votes WHERE id='$id'");
					else $result = mysql_query("SELECT id, memberid, artist, title, youtube, yes, no FROM votes ORDER BY score DESC, dtstamp DESC LIMIT $start,$limit");
					
					// Insert Vote data into table
					while($row = mysql_fetch_array($result)) {
						
						// Convert Votes to Arrays
						$supporters = explode(" ", $row['yes']);
						$opposers = explode(" ", $row['no']);
						
						// Find Popularity of Song
						$current_members = 0;
						for ($index = 0; $index < count($supporters); $index++) if ($position[$supporters[$index]] != 'Alumnus') $current_members++;
						
				?><div>
						<span style="float:right;text-align:right">
							<?php if (!(array_search($memberid, $supporters) || array_search($memberid, $opposers))) { ?>
							<a href="members/actions/vote/yes.php?id=<?php echo $row['id']; ?>">Yes</a> /
							<a href="members/actions/vote/no.php?id=<?php echo $row['id']; ?>">No</a>
							<?php } else { ?><a href="members/actions/vote/undo.php?id=<?php echo $row['id']; ?>">Undo</a><?php } ?>
							<?php if ($row['memberid'] == $memberid || $_SESSION['isofficer'] == 'yes') { ?>/ <a href="members/actions/vote/delete.php?id=<?php echo $row['id']; ?>">Delete</a><?php } ?>
							<br /><?php /* vote count */ echo count($supporters) + count($opposers) - 2; ?> votes /
							<?php /* popularity */ echo round($current_members * 100 / (count($position) - 2)); ?>%
						</span>
						<a href="members/votes.php?id=<?php echo $row['id']; ?>"><?php /* song */ echo $row['title']; ?></a>
						by <a href="http://www.last.fm/music/<?php echo str_ireplace(' ', '+', $row['artist']); ?>"><?php /* artist */ echo $row['artist']; ?></a>
						<br />Submitted by <a href="members.php?id=<?php echo $row['memberid']; ?>"><?php /* member */ echo $solo[$row['memberid']]; ?></a> /
						<a href="<?php /* video link */ echo $row['youtube']; ?>">Watch a Video</a>
					</div>
					<?php } echo "\r"; ?>
				</div>
				<?php /* Page Navigation */ include "pages.php"; ?>
				
				<form name="editbox" method="post" action="members/actions/new/vote.php">
					<div>
						<div class="input_box">
							<label for="artist">Artist</label><br />
							<input type="text" name="artist" id="artist" maxlength="32" />
						</div>
						<div class="input_box">
							<label for="Title">Title</label><br />
							<input type="text" name="title" id="title" maxlength="32" />
						</div>
						<div class="input_box">
							<label for="youtube">YouTube Video Link</label><br />
							<input type="text" name="youtube" id="youtube" maxlength="128" />
						</div>
						<span style="display: block; clear: both"><input type="submit" name="submit" value="Add Song Vote" style="display:block;margin:0 auto;" /></span>
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
