<?php /* Enable GZIP Compression */ include "/home/dulynote/public_html/members/includes/gzip_compress.php"; ?>
<?php /* Connect to MySQL Database on the PHP server */ session_start(); include "connect.php"; ?>
<?php /* Get Canvas Elements */ $canvas = mysql_fetch_array(mysql_query("SELECT logo, navbar, tray FROM canvas WHERE id=1")); ?>
<?php /* Authorize Member and Verify if Logged-In */ include "auth.php"; ?>
<?php /* Authorize Member and Verify if Officer */ include "officer_auth.php"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	
	<head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="robots" content="noindex, nofollow" />
		<title>Access Accounts on Other Sites - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
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
				<h1>Access Accounts on the Web</h1>
				<div class="shaded_div">
					<?php
					
					$page = $_REQUEST['page'];
					$id = $_REQUEST['id'];
					if ($page < 1) $page = 1;
					if ($id < 1) $id = 0;
					$limit = 30;
					$width = 7;
					$num_rows = mysql_num_rows(mysql_query("SELECT id FROM websites"));
					if ($page > $num_rows / $limit) $page = ceil($num_rows / $limit);
					$start = $limit * ($page - 1);
					$width += (floor($width / 2) == $width / 2);
					if ($id > 0) $result = mysql_query("SELECT id, name, website, username, password, deleted FROM websites WHERE id='$id'");
					else $result = mysql_query("SELECT id, name, website, username, password, deleted FROM websites ORDER BY dtstamp ASC, deleted ASC LIMIT $start,$limit");
					if ($num_rows > 0) {
						while ($row = mysql_fetch_array($result))
				{ ?><div<?php if ($row['deleted']) { ?> style="font-size:75%"<?php } ?>>
							<a href="<?php echo $row['website']; ?>"><?php echo $row['name']; ?></a> /
							<?php echo $row['username']; ?> /
							<?php echo $row['password']; ?>
							<span style="float:right">
								<?php if (!$row['deleted']) { ?><a href="members/edit/website.php?id=<?php echo $row['id']; ?>">Edit</a> /
								<a href="members/actions/delete/website.php?id=<?php echo $row['id']; ?>">Delete</a>
								<?php } else { ?><a href="members/actions/restore/website.php?id=<?php echo $row['id']; ?>">Restore</a><?php } ?>
							</span>
						</div>
						<?php }
					} else { ?><div>Oops! It looks like this website doesn't exist...</div><?php }
					echo "\r";
					
					?>
				</div>
				<?php /* Page Navigation */ include "pages.php"; ?>
				
				<form name="editbox" method="post" action="members/actions/new/website.php">
					<div>
						<div class="input_box_thin">
							<label for="webname">Website Name</label><br />
							<input type="text" name="webname" id="webname" />
						</div>
						<div class="input_box_thin">
							<label for="website">Address/URL</label><br />
							<input type="text" name="website" id="website" />
						</div>
						<div class="input_box_thin">
							<label for="username">Username</label><br />
							<input type="text" name="username" id="username" />
						</div>
						<div class="input_box_thin">
							<label for="password">Password</label><br />
							<input type="text" name="password" id="password" />
						</div>
						<span style="display: block; clear: both;"><input type="submit" name="submit" value="Add" /></span>
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
