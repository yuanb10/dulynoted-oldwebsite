<?php /* Enable GZIP Compression */ include "/home/dulynote/public_html/members/includes/gzip_compress.php"; ?>
<?php /* Connect to MySQL Database on the PHP server */ session_start(); include "connect.php"; ?>
<?php /* Get Canvas Elements */ $canvas = mysql_fetch_array(mysql_query("SELECT logo, navbar, tray FROM canvas WHERE id=1")); ?>
<?php /* Authorize Member and Verify if Logged-In */ include "auth.php"; ?>
<?php /* Authorize Member and Verify if Officer */ include "officer_auth.php"; ?>
<?php /* Store Members Full Names into Array */ include "soloists.php"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	
	<head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="robots" content="noindex, nofollow" />
		<title>Edit a Song - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
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
					
					// Search for and fetch Song from the MySQL Database
					$id = $_REQUEST['id'];
					$_SESSION['songid'] = $id;
					$row = mysql_fetch_array(mysql_query("SELECT title, artist, semester, tonic, soloid, youtube FROM repertoire WHERE id='$id' && deleted=0"));
					
					// Declare variables for data manipulation
					$year = substr($row['semester'], -4, 4);
					if (strcspn($row['semester'],'2') == 5) $season = array('0' => 'Fall', '1' => 'Spring');
					else $season = array('0' => 'Spring', '1' => 'Fall');
					
				?>
				<h1>Edit <em><a href="repertoire.php?id=<?php echo $id; ?>"><?php echo $row['title']; ?></a></em></h1>
				<form name="editbox" method="post" action="members/actions/update/song.php">
					<div>
						<div class="input_box">
							<label for="artist">Artist</label><br />
							<input type="text" name="artist" id="artist" maxlength="32" value="<?php echo $row['artist']; ?>" />
						</div>
						<div class="input_box">
							<label for="title">Title</label><br />
							<input type="text" name="title" id="title" maxlength="32" value="<?php echo $row['title']; ?>" />
						</div>
						<div class="input_box">
							<label for="season">Semester</label><br />
							<select name="season" id="season">
								<option value="<?php echo $season[0]; ?>"><?php echo $season[0]; ?></option>
								<option value="<?php echo $season[1]; ?>"><?php echo $season[1]; ?></option>
							</select>
							<select name="year" id="year">
								<option value="<?php echo $year; ?>"><?php echo $year; ?></option>
								<?php for ($x = 2008; $x <= 2999; $x++) { if ($year != $x) { ?><option value="<?php echo $x; ?>"><?php echo $x; ?></option>
								<?php } } echo "\r"; ?>
							</select>
						</div>
						<div class="input_box" style="clear:both">
							<label for="solo">Soloist</label><br />
							<select name="solo" id="solo">
								<option value="<?php echo $solo[$row['soloid']] . ' ' . $row['soloid']; ?>"><?php echo $solo[$row['soloid']] . " " . $row['soloid']; ?></option>
								<?php for ($x = 0; $x <= count($solo); $x++) { if ($x != $row['soloid']) { ?><option value="<?php echo $solo[$x] . ' ' . $x; ?>"><?php echo $solo[$x] . " " . $x; ?></option>
								<?php } } echo "\r"; ?>
							</select>
						</div>
						<div class="input_box">
							<label for="tonic">Key</label><br />
							<select name="tonic" id="tonic">
								<option value="<?php echo substr($row['tonic'], 0, 1); ?>"><?php echo substr($row['tonic'], 0, 1); ?></option>
								<?php for ($x = 0; $x < 7; $x++) { if (substr($row['tonic'], 0, 1) != substr("ABCDEFG", $x, 1)) { ?><option value="<?php echo substr('ABCDEFG', $x, 1); ?>"><?php echo substr("ABCDEFG", $x, 1); ?></option>
								<?php } } echo "\r"; ?>
							</select>
							<select name="sharp" id="sharp">
								<option value="<?php echo substr($row['tonic'], 1, 1); ?>"><?php echo substr($row['tonic'], 1, 1); ?></option>
								<?php for ($x = 0; $x < 3; $x++) { if (substr($row['tonic'], 1, 1) != substr("b #", $x, 1)) { ?><option value="<?php echo substr('b #', $x, 1); ?>"><?php echo substr("b #", $x, 1); ?></option>
								<?php } } echo "\r"; ?>
							</select>
							<select name="major" id="major">
								<option value="<?php echo substr($row['tonic'], -5, 5); ?>"><?php echo substr($row['tonic'], -5, 5); ?></option>
								<?php for ($x = 0; $x < 2; $x++) { if (substr($row['tonic'], -5, 5) != substr("MajorMinor", $x * 5, 5)) { ?><option value="<?php echo substr('MajorMinor', $x * 5, 5); ?>"><?php echo substr("MajorMinor", $x * 5, 5); ?></option>
								<?php } } echo "\r"; ?>
							</select>
						</div>
						<div class="input_box">
							<label for="youtube">YouTube Video Link</label><br />
							<input type="text" name="youtube" id="youtube" maxlength="128" value="<?php echo $row['youtube']; ?>" />
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
