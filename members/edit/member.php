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
		<title>Edit a Member - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
		<link rel="search" type="application/opensearchdescription+xml" title="Duly Noted" href="search.xml" />
		<base href="http://dulynoted.union.rpi.edu/" />
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
					
					// Search for and fetch Member from the MySQL Database
					$id = $_REQUEST['id'];
					$_SESSION['userid'] = $id;
					$row = mysql_fetch_array(mysql_query("SELECT username, firstname, lastname, email, phone, provider, class, rin, shirtsize, voicepart, position, studying, bio, schedule FROM members WHERE id='$id'"));
					
					// Search for and fetch TXT Providers from the MySQL Database
					$index = 0;
					$result2 = mysql_query("SELECT name FROM txt_providers ORDER BY name");
					while ($row2 = mysql_fetch_array($result2)) $providers[$index++] = $row2['name'];
					
					// Declare variables for data manipulation
					$shirtsizes = array("S", "M", "L", "XL", "2XL", "3XL", "4XL");
					$voiceparts = array("Tenor 1", "Tenor 2", "Baritone", "Bass 1", "Bass 2", "Vocal Percussion");
					$studying = array("On Campus", "Abroad", "Cooperative", "Internship", "Nowhere");
					$positions = array("Member", "Alumnus", "Inactive", "Musical Director", "Choreographer", "Business Manager", "Treasurer", "Public Relations", "Secretary", "Webmaster");
					
				?>
				<h1>Edit <em><a href="members.php?id=<?php echo $id; ?>"><?php echo $row['firstname'] . " " . $row['lastname']; ?></a></em></h1>
				<img src="images/image.php?src=members/member<? echo $id; ?>.jpg" alt="<?php echo $row['firstname'] . ' ' . $row['lastname']; ?>" style="width:100px;float:right" />
				<form name="editbox" method="post" action="members/actions/update/member.php">
					<input type="hidden" name="memberid" id="memberid" value="<?php echo $id; ?>" />
					<div>
						<div class="input_box">
							<label for="username">Username</label><br />
							<input type="text" name="username" id="username" maxlength="32" value="<?php echo $row['username']; ?>" />
						</div>
						<div class="input_box">
							<label for="email">Email Address</label><br />
							<input type="text" name="email" id="email" maxlength="32" value="<?php echo $row['email']; ?>" />
						</div>
						<div class="input_box">
							<label for="firstname">First Name</label><br />
							<input type="text" name="firstname" id="firstname" maxlength="32" value="<?php echo $row['firstname']; ?>" />
						</div>
						<div class="input_box">
							<label for="lastname">Last Name</label><br />
							<input type="text" name="lastname" id="lastname" maxlength="32" value="<?php echo $row['lastname']; ?>" />
						</div>
						<div class="input_box" style="clear:both">
							<label for="areacode">Phone Number</label><br />
							<input type="text" name="areacode" id="areacode" maxlength="3" style="width:40px" value="<?php echo substr($row['phone'], 0, 3); ?>" />
							<input type="text" name="prefix" id="prefix" maxlength="3" style="width:40px" value="<?php echo substr($row['phone'], 3, 3); ?>" />
							<input type="text" name="line" id="line" maxlength="4" style="width:50px" value="<?php echo substr($row['phone'], 6, 4); ?>" />
						</div>
						<div class="input_box">
							<label for="provider">Mobile Provider</label><br />
							<select name="provider">
								<option value="<?php echo $row['provider']; ?>"><?php echo $row['provider']; ?></option>
								<?php for ($index = 0; $index < count($providers); $index++) if ($row['provider'] != $providers[$index]) { ?><option value="<?php echo $providers[$index]; ?>"><?php echo $providers[$index]; ?></option>
								<?php } echo "\r"; ?>
							</select>
						</div>
						<div class="input_box" style="clear:both">
							<label for="class">Class Year</label><br />
							<select name="class">
								<option value="<?php echo $row['class']; ?>"><?php echo $row['class']; ?></option>
								<?php if ($row['class'] != 'Grad') { ?><option maxlength="4" value="Grad">Grad</option>
								<?php } echo "\r"; ?>
								<?php for ($x = 2009; $x <= 2999; $x++) if ($row['class'] != $x) { ?><option value="<?php echo $x; ?>"><?php echo $x; ?></option>
								<?php } echo "\r"; ?>
							</select>
						</div>
						<div class="input_box">
							<label for="rin"><acronym title="Rensselaer Identification Number">RIN</acronym></label><br />
							<input type="text" name="rin" id="rin" maxlength="9" value="<?php echo $row['rin']; ?>" />
						</div>
						<div class="input_box">
							<label for="studying">Studying</label><br />
							<select name="studying">
								<option maxlength="32" value="<?php echo $row['studying']; ?>"><?php echo $row['studying']; ?></option>
								<?php for ($x = 0; $x < count($studying); $x++) if ($row['studying'] != $studying[$x]) { ?><option maxlength="32" value="<?php echo $studying[$x]; ?>"><?php echo $studying[$x]; ?></option>
								<?php } echo "\r"; ?>
							</select>
						</div>
						<div class="input_box" style="clear:both">
							<label for="shirtsize">Shirt Size</label><br />
							<select name="shirtsize">
								<option value="<?php echo $row['shirtsize']; ?>"><?php echo $row['shirtsize']; ?></option>
								<?php for ($x = 0; $x < count($shirtsizes); $x++) if ($row['shirtsize'] != $shirtsizes[$x]) { ?><option value="<?php echo $shirtsizes[$x]; ?>"><?php echo $shirtsizes[$x]; ?></option>
								<?php } echo "\r" ?>
							</select>
						</div>
						<div class="input_box">
							<label for="voicepart">Voice Part</label><br />
							<select name="voicepart">
								<option value="<?php echo $row['voicepart']; ?>"><?php echo $row['voicepart']; ?></option>
								<?php for ($x = 0; $x < count($voiceparts); $x++) if ($row['voicepart'] != $voiceparts[$x]) { ?><option value="<?php echo $voiceparts[$x]; ?>"><?php echo $voiceparts[$x]; ?></option>
								<?php } echo "\r"; ?>
							</select>
						</div>
						<div class="input_box">
							<label for="position">Position</label><br />
							<select name="position">
								<option value="<?php echo $row['position']; ?>"><?php echo $row['position']; ?></option>
								<?php for ($x = 0; $x < count($positions); $x++) if ($row['position'] != $positions[$x]) { ?><option value="<?php echo $positions[$x]; ?>"><?php echo $positions[$x]; ?></option>
								<?php } echo "\r"; ?>
							</select>
						</div>
						<span style="display: block; clear: both;"><label for="bio">Bio</label> (Please keep it clean...)</span>
						<textarea name="bio" id="bio" style="width:764px;height:75px"><?php echo $row['bio']; ?></textarea>
						<br /><input type="submit" name="submit" value="Update Profile" />
					</div>
				</form>
				
				<p>
					<form action="members/actions/upload/pic.php" method="post" enctype="multipart/form-data">
						<div>
							<input type="hidden" name="memberid" id="memberid" value="<?php echo $id; ?>" />
							<label for="file">Upload <a href="members.php?id=<?php echo $id; ?>"><?php echo $row['firstname'] . " " . $row['lastname']; ?></a>'s Profile Picture:</label>
							<br /><input type="file" name="file" id="file" />
							<br /><input type="submit" name="submit" value="Upload Picture" />
						</div>
					</form>
				</p>
				
				<p>
					<table class="shaded_table" id="schedule_app">
						<caption><h2>Select When <a href="members.php?id=<?php echo $id; ?>"><?php echo $row['firstname'] . " " . $row['lastname']; ?></a> is Busy During the Week</h2></caption>
						<thead class="centered">
							<tr>
								<th width="10px"></th>
								<th>SUN</th>
								<th>MON</th>
								<th>TUE</th>
								<th>WED</th>
								<th>THU</th>
								<th>FRI</th>
								<th>SAT</th>
							</tr>
						</thead>
						<tbody>
							<?php
								
								// Search for and fetch Rehearsal(s) from the MySQL Database
								$row3 = mysql_fetch_array(mysql_query("SELECT schedule FROM rehearsals LIMIT 1"));
								
								// Insert Schedule data into table
								for ($hour = 8; $hour < 24; $hour++) { ?><tr>
								<td><?php if ($hour_mod < 10) echo 0; echo $hour - 12 * ($hour > 12); ?></td>
								<?php for ($day = 1; $day < 8; $day++)
						{ ?><td <?php if (strpos($row3['schedule'], 'd' . $day . 'h' . $hour) && strpos($row['schedule'], 'd' . $day . 'h' . $hour)) { ?>class="conflict" title="Rehearsal"<?php } else if (strpos($row['schedule'], 'd' . $day . 'h' . $hour)) { ?>class="busy"<?php } else if (strpos($row3['schedule'], 'd' . $day . 'h' . $hour)) { ?>class="rehearsal" title="Rehearsal"<?php } else { ?>class="free"<?php } ?>id="d<?php echo $day; ?>h<?php echo $hour; ?>" onclick="scheduleMod(<?php echo $day; ?>,<?php echo $hour; ?>)"></td>
								<?php } ?>
							</tr>
							<?php } echo "\r"; ?>
						</tbody>
					</table>
					<form name="editbox2" method="post" action="members/actions/update/schedule.php">
						<div class="centered">
							<input type="hidden" name="schedule" id="schedule" value="<?php echo $row['schedule']; ?>" />
							<input type="hidden" name="memberid" id="memberid" value="<?php echo $id; ?>" />
							<input type="submit" value="Update Weekly Schedule" />
							<input type="button" value="Clear Schedule" onclick="clearSchedule()" />
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
