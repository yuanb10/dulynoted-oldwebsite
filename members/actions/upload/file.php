<?php /* Enable GZIP Compression */ include "/home/dulynote/public_html/members/includes/gzip_compress.php"; ?>
<?php /* Connect to MySQL Database on the PHP server */ session_start(); include "connect.php"; ?>
<?php /* Get Canvas Elements */ $canvas = mysql_fetch_array(mysql_query("SELECT logo, navbar, tray FROM canvas WHERE id=1")); ?>
<?php /* Authorize Member and Verify if Logged-In */ include "auth.php"; ?>
<?php /* Form Validation */ include "validate.php"; ?>
<?php /* Rename Non-Unique Filename to Unique Name */ include "rename_file.php"; ?>
<?php /* Clean Bad Characters in Variables */ include "cleanse_variable.php"; ?>
<?php /* Get IP Address of Logged-In Member */ include "ip_address.php"; ?>
<?php /* Get Member ID of Logged-In Member */ include "memberid.php"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	
	<head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="robots" content="noindex, nofollow" />
		<title>Upload File - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
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
				$description = cleanse ($_POST["description"]);
				$date = date("Y/m/d");
				
				// Return Error if file upload failed
				if ($_FILES["file"]["error"] > 0) header ("location: http://dulynoted.union.rpi.edu/members/includes/error.php?error=File error " . $_FILES["file"]["error"] . " has occurred.");
				else {
				
				// Rename File if filename is not unique
				if (file_exists("/home/dulynote/public_html/members/uploads/" . $_FILES["file"]["name"])) $filename = FileRename ($_FILES["file"]["name"]);
				else $filename = $_FILES["file"]["name"];
				
				// Move Uploaded File to Uploads directory
				move_uploaded_file($_FILES["file"]["tmp_name"], "/home/dulynote/public_html/members/uploads/" . $filename);
				
				// Get File Size
				$filesize = filesize("/home/dulynote/public_html/members/uploads/" . $filename);
				
				// Validate Posted Variables
				validate(array($description, $memberid));
				
				// Update Upload on the MySQL Database
				mysql_query("INSERT INTO uploads (filename, filesize, description, memberid, date) VALUES ('$filename', '$filesize', '$description', '$memberid', '$date')");
				
				// Search for and fetch Upload from the MySQL Database
				$upload = mysql_fetch_array(mysql_query("SELECT id FROM uploads ORDER BY id DESC"));
				
				// Setup Member Log
				$action = 'uploaded file <a href="members/uploads.php?id=' . $upload['id'] . '">' . $filename . '</a>';
				
				// Add to the Members Logs
				mysql_query("INSERT INTO logs (memberid, action, ip) VALUES ('$memberid', '$action', '$ip')");
				
				?>
				
				<h1><a href="members/uploads.php?id=<?php echo $upload['id']; ?>"><?php echo $description; ?></a> Uploaded</h1>
				<p class="centered">
					<a href="members/uploads.php?id=<?php echo $upload['id']; ?>"><?php echo $description; ?></a> has been successfully uploaded.<br />
					<a href="members/uploads.php">Go Back</a>
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
