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
		<title>Edit Album - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
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
					
					// Search for and fetch Album from the MySQL Database
					$id = $_REQUEST['id'];
					$row = mysql_fetch_array(mysql_query("SELECT id, album, tracks, semester FROM discography WHERE id='$id' && deleted=0"));
					$_SESSION['albumid'] = $row['id'];
					$tracks = unserialize($row['tracks']);
					
					// Search for and fetch Members from the MySQL Database
					$options[$x = 0] = 'NO SOLOIST';
					$result2 = mysql_query("SELECT firstname, lastname FROM members ORDER BY lastname");
					while ($row2 = mysql_fetch_array($result2)) $options[++$x] = $row2['firstname'] . ' ' . $row2['lastname'];
					
					// Declare variables for data manipulation
					for ($x = 0; $x < count($options); $x++) $options_html .= "<option value=\"" . $options[$x] . "\">" . $options[$x] . "</option>";
					$year = substr($row['semester'], -4, 4);
					if (strcspn($row['semester'],'2') == 5) $season = array('0' => 'Fall', '1' => 'Spring');
					else $season = array('0' => 'Spring', '1' => 'Fall');
					
				?>
				<h1>Edit <em><a href="discography.php?id=<?php echo $id; ?>"><?php echo $row['album']; ?></a></em></h1>
				<form name="editbox" id="newalbum" method="post" action="members/actions/update/album.php">
					<div>
						<div class="input_box">
							<label for="album">Album Name</label><br />
							<input type="text" name="album" id="album" value="<?php echo $row['album']; ?>" />
						</div>
						<div class="input_box">
							<label for="season">Semester</label><br />
							<select name="season" id="season">
								<option value="<?php echo $season[0]; ?>"><?php echo $season[0]; ?></option>
								<option value="<?php echo $season[1]; ?>"><?php echo $season[1]; ?></option>
							</select>
							<select name="year" id="year">
								<option value="<?php echo $year; ?>"><?php echo $year; ?></option>
								<?php for ($x = 2009; $x <= 2999; $x++) if ($year != $x) echo "			<option value=\"" . $x . "\">" . $x . "</option>\n\t\t\t\t\t"; ?>
							</select>
						</div>
						<span style="display: block; clear: both"></span>
						<?php
							for ($track = 1; $track <= count($tracks); $track++)
				{ ?><label for="track<?php echo $track; ?>">Track <?php echo $track; ?>:</label>
						<input type="text" name="track<?php echo $track; ?>" id="track<?php echo $track; ?>" value="<?php /* Title */ echo $tracks[$track][0]; ?>" /> by
						<input type="text" name="artist<?php echo $track; ?>" id="artist<?php echo $track; ?>" value="<?php /* Artist */ echo $tracks[$track][1]; ?>" /> with Soloist:
						<select name="solo<?php echo $track; ?>" id="solo<?php echo $track; ?>">
							<option value="<?php /* Soloist */ echo $tracks[$track][2]; ?>"><?php echo $tracks[$track][2]; ?></option>
							<?php for ($x = 0; $x < count($options); $x++) if ($options[$x] != $tracks[$track][2]) echo "			<option value=\"" . $options[$x] . "\">" . $options[$x] . "</option>\n\t\t\t\t"; ?>
						</select><br />
						<?php } echo "\r"; ?>
						<input type="hidden" name="soloists" id="soloists" value="<?php echo htmlentities($options_html); ?>" />
						<input type="hidden" name="tracknum" id="tracknum" value="<?php echo count($tracks); ?>" />
						<br /><input type="button" name="addtrack" id="addtrack" value="Add Another Track" />
						<br /><input type="submit" name="submit" value="Update Album" style="display:block;margin:0 auto;" />
					</div>
				</form>
				
				<p>
					<form action="members/actions/upload/artwork.php" method="post" enctype="multipart/form-data">
						<div>
							<label for="file">Upload Album Artwork:</label>
							<br /><input type="file" name="file" id="file" />
							<br /><input type="hidden" name="albumid" id="albumid" value="<?php echo $id; ?>" />
							<input type="submit" name="submit" value="Upload Picture" />
						</div>
					</form>
				</p>
				
			</div>
			
			<!-- Bottom Navigation Tray -->
			<?php echo $canvas['tray'] . "\r"; ?>
			
			<!-- Footnotes -->
			<?php include "footnotes.php"; ?>
			
		</div>
	</body>
	
</html>
<?php /* Close connection to the MySQL Database */ mysql_close($connection); ?>
