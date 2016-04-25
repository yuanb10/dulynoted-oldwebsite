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
				
				<div class="gallery">
					<?php
					
					// Search for and fetch Officer(s) from the MySQL Database
					$result = mysql_query("SELECT id, firstname, lastname, email, position FROM members WHERE position='Music Director' || position='Business Manager' ORDER BY position");
					
					// Calculate Left and Right Margins
					$num_rows = mysql_num_rows($result);
					$margin_width = (770 - 110 * $num_rows) / (2 * $num_rows);
					
					// Insert Officer data into table
					while ($row = mysql_fetch_array($result))
			{ ?><div style="margin: 0px <?php echo $margin_width; ?>px 0px <?php echo $margin_width; ?>px">
						<a href="mailto:<?php echo $row['email']; ?>"><img class="icon_big" src="images/image.php?src=members/member<?php echo $row['id']; ?>.jpg" alt="<?php echo $row['email']; ?>" /></a>
						<br /><em><?php echo $row['position']; ?></em>
						<br /><a href="members.php?id=<?php echo $row['id']; ?>"><?php echo $row['firstname' ] . " " . $row['lastname']; ?></a></span>
					</div>
					<?php } echo "\r"; ?>
				</div>
				
				<div style="clear:both"></div>
				
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
