<?php /* Enable GZIP Compression */ include "/home/dulynote/public_html/members/includes/gzip_compress.php"; ?>
<?php /* Connect to MySQL Database on the PHP server */ session_start(); include "connect.php"; ?>
<?php /* Get Canvas Elements */ $canvas = mysql_fetch_array(mysql_query("SELECT logo, navbar, tray FROM canvas WHERE id=1")); ?>
<?php /* Authorize Member and Verify if Logged-In */ include "auth.php"; ?>
<?php /* Display Time since given Datetime */ include "timestamper.php"; ?>
<?php /* Store Members Full Names into Array */ include "soloists.php"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	
	<head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="robots" content="noindex, nofollow" />
		<title>The Shoutbox - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
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
				<h1>The Shoutbox</h1>
				<h2><em>Say whatever you'd like&mdash; we're behind closed doors!</em></h2>
				<div class="shaded_div">
					<?php
					
					// Obtain page # or item ID from URL
					$page = $_REQUEST['page'];
					$id = $_REQUEST['id'];
					if ($page < 1) $page = 1;
					if ($id < 1) $id = 0;
					
					// Set table size and scroll width
					list ($limit, $width) = array (15, 7);
					
					// Determine position of first (top) item on table
					$num_rows = mysql_num_rows(mysql_query("SELECT id FROM shoutbox"));
					if ($page > $num_rows / $limit) $page = ceil($num_rows / $limit);
					$start = $limit * ($page - 1);
					$width += (floor($width / 2) == $width / 2);
					
					// Search for and fetch Shout(s) from the MySQL Database
					if ($id > 0) $result = mysql_query("SELECT id, memberid, content, dtstamp FROM shoutbox WHERE id='$id'");
					else $result = mysql_query("SELECT id, memberid, content, dtstamp FROM shoutbox ORDER BY dtstamp DESC LIMIT $start,$limit");
					
					// Insert Shout data into table
					if ($num_rows > 0) {
						while ($row = mysql_fetch_array($result))
			{ ?><div style="padding-right:10px; clear:both;">
						<img class="headshot_small" src="images/image.php?src=members/member<?php echo $row['memberid']; ?>.jpg" />
						<span style="font-weight:bold"><a href="members.php?id=<?php echo $row['memberid']; ?>"><?php echo $solo[$row['memberid']]; ?></a></span>
						<?php echo $row['content']; ?>
						<br /><span style="font-size:75%;"><?php echo timestamper($row['dtstamp']); if ($row['memberid'] == $_SESSION['memberid'] || $_SESSION['isofficer'] == 'yes'): ?> - <a href="members/actions/delete/shout.php?id=<?php echo $row['id']; ?>">Delete</a><?php endif; ?></span>
					</div>
						<?php }
					} else echo "<div>Oops! It looks like this shout doesn't exist...</div>\n\t\t\t\t\t";
					echo "\r";
					
					?>
				</div>
				<?php /* Page Navigation */ include "pages.php"; ?>
				
				<form name="editbox" method="post" action="members/actions/new/shout.php">
					<div>
						<textarea name="content" id="content" maxlength="512" style="width:770px; height:50px;"></textarea>
						<input type="submit" name="submit" value="Shout!" style="display:block;margin:0 auto;" />
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
