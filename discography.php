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
				
				<table class="shaded_table">
					<?php
					
					// Obtain page # or item ID from URL
					$page = $_REQUEST['page'];
					$id = $_REQUEST['id'];
					if ($page < 1) $page = 1;
					if ($id < 1) $id = 0;
					
					// Set table size and scroll width
					list ($limit, $width) = array (3, 5);
					
					// Determine position of first (top) item on table
					$num_rows = mysql_num_rows(mysql_query("SELECT id FROM " . $webpage['searchable']));
					if ($page > $num_rows / $limit) $page = ceil($num_rows / $limit);
					$start = $limit * ($page - 1);
					$width += (floor($width / 2) == $width / 2);
					
					if ($id > 0) {
						
						// Search for and fetch Album from the MySQL Database
						$row = mysql_fetch_array(mysql_query("SELECT deleted FROM discography WHERE id='$id'"));
						
						// Return Error if Album deleted
						if ($row['deleted'] || count($row) == 1) header ('location: 404.shtml');
					
					}
					
					// Search for and fetch Album(s) from the MySQL Database
					if ($id > 0) $result = mysql_query("SELECT id, album, tracks FROM " . $webpage['searchable'] . " && id='$id'");
					else $result = mysql_query("SELECT id, album, tracks FROM " . $webpage['searchable'] . " ORDER BY date DESC, album LIMIT $start,$limit");
					
					// Insert Album data into table
					if ($num_rows > 0) {
						while($row = mysql_fetch_array($result)) {
							$tracks = unserialize($row['tracks']);
				?><tr>
						<td class="albumart">
							<img src="images/image.php?src=discography/album<?php echo $row['id']; ?>.jpg\" alt="<?php echo $row['album']; ?>" style="display:block; margin: 10px 10px 10px 0px; float: left; width: 250px" /></a>
							<table class="tracks">
								<caption><h2><big><a href="discography.php?id=<?php echo $row['id']; ?>"><?php echo $row["album"]; ?></a></big></h2></caption>
								<thead>
									<tr>
										<th></th>
										<th>Track</th>
										<th>Artist</th>
										<th>Soloist</th>
									</tr>
								</thead>
								<tbody>
									<?php /* Display all tracks on Album */ for ($x = 1; $x <= count($tracks); $x++) { ?><tr>
										<td><?php /* Track # */ echo $x; ?></td>
										<td><?php /* Song */ echo $tracks[$x][0]; ?></td>
										<td><?php /* Artist */ echo $tracks[$x][1]; ?></td>
										<td><?php /* Soloist */ echo $tracks[$x][2]; ?></td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</td>
					</tr>
					<?php }
					} else echo "<tr>\n\t\t\t\t\t\t<td><img src=\"images/image.php?src=discography/unknown.jpg\" /></td>\n\t\t\t\t\t\t<td>Oops! It looks like this album does not exist...</td>\n\t\t\t\t\t</tr>\n\t\t\t\t\t\t";
					echo "\r";
					
					?>
				</table>
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
