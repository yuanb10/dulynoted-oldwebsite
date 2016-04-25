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
		<title>Upload Profile Picture - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
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
				$id = cleanse ($_POST['memberid']);
				
				// Search for and fetch Member from the MySQL Database
				$member = mysql_fetch_array(mysql_query("SELECT id, username, firstname, lastname FROM members WHERE id='$id'"));
				
				// Return Error if file upload failed
				if ($_FILES["file"]["error"] > 0) header ("location: http://dulynoted.union.rpi.edu/members/includes/error.php?error=File error " . $_FILES["file"]["error"] . " has occurred.");
				else if ($_FILES["file"]["type"] != "image/jpeg" && $_FILES["file"]["type"] != "image/pjpeg" && $_FILES["file"]["type"] != "image/gif") header ("location: http://dulynoted.union.rpi.edu/members/includes/error.php?error=The file uploaded is not a valid image file!");
				else {
				
				// Get Filename of Profile Picture
				$filename = "member" . $member['id'] . ".jpg";
				
				// Validate Posted Variables
				validate(array($id, $memberid));
				
				// Delete old Profile Picture
				unlink("/home/dulynote/public_html/images/members/" . $filename);
				
				// Move Uploaded File to Profile Picture directory
				move_uploaded_file($_FILES["file"]["tmp_name"], "/home/dulynote/public_html/images/members/" . $filename);
				
				// Setup Member Log
				if ($id == $memberid) $action = 'changed his profile picture';
				else $action = 'changed <a href="members.php?id=' . $id . '">' . $member['firstname'] . ' ' . $member['lastname'] . '</a>\'s profile picture';
				
				// Add to the Members Logs
				mysql_query("INSERT INTO logs (memberid, action, ip) VALUES ('$memberid', '$action', '$ip')");
				
				// Get Filename of Profile Picture
				$filename = "/home/dulynote/public_html/images/members/" . $filename;
				
				// Get Image Size of Profile Picture
				list($width, $height) = getimagesize($filename);
				
				// Resize Profile Picture if its width is greater than 100 pixels
				if ($width != 100) {
					
					// Find resize parameters
					$percent = 100 / $width;
					$new_width = 100;
					$new_height = $height * $percent;
					
					// Create new image
					$image_p = imagecreatetruecolor($new_width, $new_height);
					
					// Open Profile Picture
					$image = imagecreatefromjpeg($filename);
					
					// Resize Profile Picture
					imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
					
					// Save resized Profile Picture to original file
					imagejpeg($image_p, $filename, 100);
					
					// Get new Image Height
					$height = $new_height;
					
				}
				
				?>
				
				<h1><a href="members.php?id=<?php echo $id; ?>"><?php if ($id == $memberid) echo "Your"; else echo $member['firstname'] . ' ' . $member['lastname']; ?></a><?php if ($id != $memberid) echo "'s"; ?> Profile Picture Uploaded</h1>
				<p class="centered">
					<a href="members.php?id=<?php echo $id; ?>"><?php if ($id == $memberid) echo "Your"; else echo $member['firstname'] . ' ' . $member['lastname']; ?></a><?php if ($id != $memberid) echo "'s"; ?> profile picture has been successfully uploaded.<br />
					<a href="members/<?php if ($id == $memberid) echo 'index'; else echo 'members'; ?>.php">Go Back</a>
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
