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
		<title>Add a New Song - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
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
				<h1>New Song</h1>
				<form name="editbox" method="post" action="members/actions/new/song.php">
					<div>
						<div class="input_box">
							<label for="artist">Artist</label><br />
							<input type="text" name="artist" id="artist" maxlength="32" />
						</div>
						<div class="input_box">
							<label for="Title">Title</label><br />
							<input type="text" name="title" id="title" maxlength="32" />
						</div>
						<div class="input_box">
							<label for="season">Semester</label><br />
							<select name="season" id="season">
								<option value="Fall">Fall</option>
								<option value="Spring">Spring</option>
							</select>
							<select name="year" id="year">
								<?php for ($year = 2008; $year <= 2999; $year++) { ?><option value="<?php echo $year; ?>"><?php echo $year; ?></option>
								<?php } echo "\r"; ?>
							</select>
						</div>
						<div class="input_box" style="clear:both">
							<label for="solo">Soloist</label><br />
							<select name="solo" id="solo">
								<?php $soloist_offset = 0;
								      for ($x = 0; $x - $soloist_offset < count($solo); $x++) {
									      if (!$solo[$x]) {
										      $soloist_offset++;
											  continue;
										  }?><option value="<?php echo $solo[$x] . ' ' . $x; ?>"><?php echo $solo[$x] . " " . $x; ?></option>
								<?php } echo "\r"; ?>
							</select>
						</div>
						<div class="input_box">
							<label for="tonic">Key</label><br />
							<select name="tonic" id="tonic">
								<?php for ($tonic = 0; $tonic < 7; $tonic++) { ?><option value="<?php echo substr('ABCDEFG', $tonic, 1); ?>"><?php echo substr("ABCDEFG", $tonic, 1); ?></option>
								<?php } echo "\r"; ?>
							</select>
							<select name="sharp" id="sharp">
								<option value="b">b</option>
								<option value=" "> </option>
								<option value="#">#</option>
							</select>
							<select name="major" id="major">
								<option value="Major">Major</option>
								<option value="Minor">Minor</option>
							</select>
						</div>
						<div class="input_box">
							<label for="youtube">YouTube Video Link</label><br />
							<input type="text" name="youtube" id="youtube" maxlength="128" />
						</div>
						<span style="display: block; clear: both;"><input type="submit" name="submit" value="Add Song" style="display:block;margin:0 auto;" /></span>
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
