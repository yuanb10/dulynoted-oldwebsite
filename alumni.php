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
				<?php echo $webpage['content'] . "\r"; ?>
				
				<div class="shaded_div">
					<?php
					
					// Obtain page # or item ID from URL
					$page = $_REQUEST['page'];
					$id = $_REQUEST['id'];
					if ($page < 1) $page = 1;
					if ($id < 1) $id = 0;
					
					// Set table size and scroll width
					list ($limit, $width) = array (10, 5);
					
					// Determine position of first (top) item on table
					$num_rows = mysql_num_rows(mysql_query("SELECT id FROM " . $webpage['searchable']));
					if ($page > $num_rows / $limit) $page = ceil($num_rows / $limit);
					$start = $limit * ($page - 1);
					$width += (floor($width / 2) == $width / 2);
					
					if ($id > 0) {
						
						// Search for and fetch Member from the MySQL Database
						$row = mysql_fetch_array(mysql_query("SELECT position FROM members WHERE id='$id'"));
						
						// Redirect Current Members to members page
						if ($row['position'] != 'Alumnus' && $row['position'] != 'Inactive') header('location: members.php?id=' . $id);
						
						// Return Error if Member doesn't exist
						if (count($row) == 1) header ('location: 404.shtml');
					
					}
					
					// Search for and fetch Member(s) from the MySQL Database
					if ($id > 0) $result = mysql_query("SELECT id, username, firstname, lastname, class, voicepart, bio FROM " . $webpage['searchable'] . " && id='$id'");
					else $result = mysql_query("SELECT id, firstname, lastname, class, voicepart, bio FROM " . $webpage['searchable'] . " ORDER BY lastname LIMIT $start,$limit");
					
					// Insert Member data into table
					while ($row = mysql_fetch_array($result))
			{ ?><div style="min-height:<?php $size = getimagesize('/home/dulynote/public_html/images/members/member' . $row['id'] . '.jpg'); echo $size[1] + 10; ?>px; padding-right:10px;">
						<img class="headshot" alt="<?php echo $row['firstname'] . ' ' . $row['lastname']; ?>" src="images/image.php?src=members/member<?php echo $row['id']; ?>.jpg" style="height:<?php echo $size[1]; ?>px;" />
						<span style="font-weight:bold;"><a href="members.php?id=<?php echo $row['id']; ?>"><?php echo $row["firstname"] . " " . $row["lastname"]; ?></a> <?php echo $row["class"]; ?></span>, <?php echo $row["voicepart"]; ?>
						<br /><em><?php echo $row["bio"]; ?></em>
					</div>
					<?php } echo "\r"; ?>
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
