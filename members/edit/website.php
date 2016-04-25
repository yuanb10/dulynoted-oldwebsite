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
		<title>Edit Access Info for a Website - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
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
					
					// Search for and fetch Post from the MySQL Database
					$id = $_REQUEST['id'];
					$_SESSION['websiteid'] = $id;
					$row = mysql_fetch_array(mysql_query("SELECT id, name, website, username, password FROM websites WHERE id='$id' && deleted=0"));
					
				?>
				<h1>Edit <em><a href="websites.php?id=<?php echo $id; ?>"><?php echo $row['name']; ?></a></em></h1>
				<form name="editbox" method="post" action="members/actions/update/website.php">
					<div>
						<div class="input_box_thin">
							<label for="name">Name</label><br />
							<input type="text" name="name" id="name" readonly="readonly" value="<?php echo $row['name']; ?>" />
						</div>
						<div class="input_box_thin">
							<label for="website">Address</label><br />
							<input type="text" name="website" id="website" readonly="readonly" value="<?php echo $row['website']; ?>" />
						</div>
						<div class="input_box_thin">
							<label for="username">Username</label><br />
							<input type="text" name="username" id="username" value="<?php echo $row['username']; ?>" />
						</div>
						<div class="input_box_thin">
							<label for="password">Password</label><br />
							<input type="text" name="password" id="password" value="<?php echo $row['password']; ?>" />
						</div>
						<span style="display: block; clear: both;"><input type="submit" name="submit" value="Update" style="display:block;margin:0 auto;" /></span>
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