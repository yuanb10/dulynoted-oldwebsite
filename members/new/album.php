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
		<title>Add a New Album - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
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
				<h1>New Album</h1>
				<form name="editbox" id="newalbum" method="post" action="members/actions/new/album.php">
					<div>
						<div class="input_box">
							<label for="album">Album Name</label><br />
							<input type="text" name="album" id="album" class="required" />
						</div>
						<div class="input_box">
							<label for="season">Semester</label><br />
								<select name="season" id="season">
								<option value="Fall">Fall</option>
								<option value="Spring">Spring</option>
							</select>
							<select name="year" id="year">
								<?php for ($x = 2009; $x <= 2999; $x++) echo "<option value=\"" . $x . "\">" . $x . "</option>\n\t\t\t\t\t\t\t\t"; echo "\r"; ?>
							</select>
						</div>
						<span style="display: block; clear: both"></span>
						<label for="track1"><b>Track 1:</b></label>
						<input type="text" name="track1" id="track1" /> by
						<input type="text" name="artist1" id="artist1" /> with Soloist:
						<select name="solo1" id="solo1">
							<option value="NO SOLOIST">NO SOLOIST</option>
							<?php $result = mysql_query("SELECT firstname, lastname FROM members ORDER BY lastname"); while ($row = mysql_fetch_array($result)) $options .= '<option value="' . $row['firstname'] . ' ' . $row['lastname'] . '">' . $row['firstname'] . ' ' . $row['lastname'] . '</option>'; echo $options; ?>
						</select>
						<input type="hidden" name="soloists" id="soloists" value="<?php echo htmlentities($options); ?>" />
						<input type="hidden" name="tracknum" id="tracknum" value="1" />
						<br /><input type="button" name="addtrack" id="addtrack" value="Add Another Track" />
						<br /><input type="submit" name="submit" value="Add Album" style="display:block;margin:0 auto;" />
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
