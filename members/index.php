<?php /* Enable GZIP Compression */ include "/home/dulynote/public_html/members/includes/gzip_compress.php"; ?>
<?php /* Connect to MySQL Database on the PHP server */ session_start(); include "connect.php"; ?>
<?php /* Get Canvas Elements */ $canvas = mysql_fetch_array(mysql_query("SELECT logo, navbar, tray FROM canvas WHERE id=1")); ?>
<?php /* Authorize Member and Verify if Logged-In */ include "auth.php"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	
	<head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="robots" content="noindex, nofollow" />
		<title>Members Area - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
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
				
				<?php
					
					// Search for and fetch Member from the MySQL Database
					$id = $_SESSION['memberid'];
					$_SESSION['userid'] = $id;
					$row = mysql_fetch_array(mysql_query("SELECT id, username, firstname, lastname, email, phone, provider, class, rin, voicepart, position, shirtsize, studying, bio, schedule FROM members WHERE id='$id'"));
					$row2 = mysql_fetch_array(mysql_query("SELECT schedule FROM rehearsals LIMIT 1"));
					
					// Setup Student & Officer Authorizations
					if ($row['position'] != "Member" && $row['position'] != "Alumnus" && $row['position'] != "Inactive") $_SESSION['isofficer'] = "yes";
					if ($row['position'] != "Alumnus" && $row['position'] != "Inactive") $_SESSION['isstudent'] = "yes";
					
					// Declare variables for data manipulation
					$shirtsizes = array("S", "M", "L", "XL", "2XL", "3XL", "4XL");
					$voiceparts = array("Tenor 1", "Tenor 2", "Baritone", "Bass 1", "Bass 2", "Vocal Percussion");
					$studying = array("On Campus", "Abroad", "Cooperative", "Internship", "Nowhere");
					$email = $row['email'];
					
				?>
				<?php /* Display Members Area Navigation Menu */ include "menu.php"; ?>
				<h1>Members Area</h1>
				<img src="images/image.php?src=members/member<?php echo $row['id']; ?>.jpg" alt="<?php echo $row['firstname'] . ' ' . $row['lastname']; ?>" style="width:100px;float:right" />
				<form name="editbox" method="post" action="members/actions/update/profile.php">
					<div>
						<div class="input_box">
							<label for="areacode">Phone Number</label><br />
							<input type="text" name="areacode" id="areacode" maxlength="3" style="width:40px" value="<?php echo substr($row['phone'], 0, 3); ?>" />
							<input type="text" name="prefix" id="prefix" maxlength="3" style="width:40px" value="<?php echo substr($row['phone'], 3, 3); ?>" />
							<input type="text" name="line" id="line" maxlength="4" style="width:50px" value="<?php echo substr($row['phone'], 6, 4); ?>" />
						</div>
						<div class="input_box">
							<label for="provider">Mobile Provider</label><br />
							<select name="provider">
								<option maxlength="64" value="<?php echo $row['provider']; ?>"><?php echo $row['provider']; ?></option>
								<?php $result2 = mysql_query("SELECT name FROM txt_providers ORDER BY name"); while ($providers = mysql_fetch_array($result2)) if ($row['provider'] != $providers['name']) { ?><option maxlength="64" value="<?php echo $providers['name']; ?>"><?php echo $providers['name']; ?></option>
								<?php } echo "\r"; ?>
							</select>
						</div>
						<div class="input_box" style="clear: left;">
							<label for="class">Class Year</label><br />
							<select name="class">
								<option maxlength="4" value="<?php echo $row['class']; ?>"><?php echo $row['class']; ?></option>
								<?php if ($row['class'] != 'Grad') { ?><option maxlength="4" value="Grad">Grad</option>
								<?php } echo "\r"; ?>
								<?php for ($x = 2009; $x <= 2999; $x++) if ($row['class'] != $x) { ?><option maxlength="4" value="<?php echo $x; ?>"><?php echo $x; ?></option>
								<?php } echo "\r"; ?>
							</select>
						</div>
						<div class="input_box">
							<label for="rin"><acronym title="Rensselaer Identification Number">RIN</acronym></label><br />
							<input type="text" name="rin" id="rin" maxlength="9" value="<?php echo $row['rin']; ?>" />
						</div>
						<div class="input_box" style="clear: left;">
							<label for="shirtsize">Shirt Size</label><br />
							<select name="shirtsize">
								<option maxlength="3" value="<?php echo $row['shirtsize']; ?>"><?php echo $row['shirtsize']; ?></option>
								<?php for ($x = 0; $x < count($shirtsizes); $x++) if ($row['shirtsize'] != $shirtsizes[$x]) { ?><option maxlength="3" value="<?php echo $shirtsizes[$x]; ?>"><?php echo $shirtsizes[$x]; ?></option>
								<?php } echo "\r" ?>
							</select>
						</div>
						<div class="input_box">
							<label for="voicepart">Voice Part</label><br />
							<select name="voicepart">
								<option maxlength="32" value="<?php echo $row['voicepart']; ?>"><?php echo $row['voicepart']; ?></option>
								<?php for ($x = 0; $x < count($voiceparts); $x++) if ($row['voicepart'] != $voiceparts[$x]) { ?><option maxlength="32" value="<?php echo $voiceparts[$x]; ?>"><?php echo $voiceparts[$x]; ?></option>
								<?php } echo "\r"; ?>
							</select>
						</div>
						<div class="input_box">
							<label for="studying">Studying</label><br />
							<select name="studying">
								<option maxlength="32" value="<?php echo $row['studying']; ?>"><?php echo $row['studying']; ?></option>
								<?php for ($x = 0; $x < count($studying); $x++) if ($row['studying'] != $studying[$x]) { ?><option maxlength="32" value="<?php echo $studying[$x]; ?>"><?php echo $studying[$x]; ?></option>
								<?php } echo "\r"; ?>
							</select>
						</div>
						<span style="display: block; clear: both; float: left;"><label for="bio">Bio</label> (Please keep it clean...)</span>
						<br /><textarea name="bio" id="bio" maxlength="1000" style="width:764px;height:75px"><?php echo $row['bio']; ?></textarea>
						<br />
						<input type="hidden" name="username" id="username" readonly="readonly" maxlength="32" value="<?php echo $row['username']; ?>" />
						<input type="hidden" name="firstname" id="firstname" readonly="readonly" maxlength="32" value="<?php echo $row['firstname']; ?>" />
						<input type="hidden" name="lastname" id="lastname" readonly="readonly" maxlength="32" value="<?php echo $row['lastname']; ?>" />
						<input type="hidden" name="email" id="email" readonly="readonly" maxlength="32" value="<?php echo $row['email']; ?>" />
						<input type="hidden" name="position" id="position" readonly="readonly" value="<?php echo $row['position']; ?>" />
						<input type="submit" name="submit" value="Update Profile" style="display:block;margin:0 auto;" />
					</div>
				</form>
				
				<p>
					<form class="upload_pic" action="members/actions/upload/pic.php" method="post" enctype="multipart/form-data">
						<div>
							<input type="hidden" name="memberid" id="memberid" value="<?php echo $id; ?>" />
							<label for="file">Upload Profile Picture:</label> (JPEG or GIF)
							<br /><input type="file" name="file" id="file" />
							<br /><span style="font-size: 85%"><em>Don't know how to edit your photos?</em> Try one of these: <a href="https://www.photoshop.com/">Adobe Photoshop</a>, <a href="http://www.apple.com/ilife/iphoto/">Apple iPhoto</a>, <a href="http://picasa.google.com/">Google Picasa</a>, <a href="http://www.gimp.org/"><abbr title="GNU Image Manipulation Program">GIMP</abbr></a>, <a href="http://www.picnik.com/">Picnik</a>, or <a href="http://fotoflexer.com/">FotoFlexer</a>.</span>
							<br /><input type="submit" name="submit" value="Upload Picture" />
						</div>
					</form>
				</p>
				
				<p>
					<table class="shaded_table" id="schedule_app">
						<caption><h2>Select When You're Busy During the Week</h2></caption>
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
								
								// Display Weekly Schedule
								for ($hour = 8; $hour < 24; $hour++):
									$hour_mod = $hour - 12 * ($hour > 12);
									if ($hour_mod < 10) $hour_mod = '0' . $hour_mod; ?>
<tr>
								<td><?php echo $hour_mod; ?></td>
<?php for ($day = 1; $day < 8; $day++): ?>
								<td <?php if (strpos($row2['schedule'], 'd' . $day . 'h' . $hour) && strpos($row['schedule'], 'd' . $day . 'h' . $hour)): ?>class="conflict" title="Rehearsal"<?php elseif (strpos($row['schedule'], 'd' . $day . 'h' . $hour)): ?>class="busy"<?php elseif (strpos($row2['schedule'], 'd' . $day . 'h' . $hour)): ?>class="rehearsal" title="Rehearsal"<?php else: ?>class="free"<?php endif; ?> id="d<?php echo $day; ?>h<?php echo $hour; ?>" onclick="scheduleMod(<?php echo $day; ?>,<?php echo $hour; ?>)"></td>
<?php endfor; ?>
							</tr>
							<?php endfor; echo "\r"; ?>
						</tbody>
					</table>
					<form name="editbox" method="post" action="members/actions/update/schedule.php">
						<div class="centered">
							<input type="hidden" name="schedule" id="schedule" value="<?php echo $row['schedule']; ?>" />
							<input type="hidden" name="memberid" id="memberid" value="<?php echo $id; ?>" />
							<input type="submit" value="Update Weekly Schedule" />
							<input type="button" value="Clear Schedule" onclick="clearSchedule()" />
						</div>
					</form>
				</p>
				
				<p>
					<fieldset class="loginbox" style="float:right;">
						<legend><em>Change Email Address</em></legend>
						<form name="login" method="post" action="members/actions/change/email.php" style="display:block;">
							<div>
								<input name="old_email" type="hidden" id="old_email" value="<?php echo $email; ?>" />
								<label for="new_email">New Email</label>
								<br /><input name="new_email" type="text" id="new_email" />
								<br /><label for="password">Password</label>
								<br /><input name="password" type="password" id="password" />
								<br /><label for="confirm">Confirm Password</label>
								<br /><input name="confirm" type="password" id="confirm" />
								<br /><input type="submit" name="Submit" value="Change Email Address" />
							</div>
						</form>
					</fieldset>
				</p>
				
				<p>
					<fieldset class="loginbox">
						<legend><em>Change Password</em></legend>
						<form name="login" method="post" action="members/actions/change/password.php" style="display:block;">
							<div>
								<input name="username" type="hidden" id="username" value="<?php echo $row['username']; ?>" />
								<label for="old_password">Old Password</label>
								<br /><input name="old_password" type="password" id="old_password" />
								<br /><label for="new_password">New Password</label>
								<br /><input name="new_password" type="password" id="new_password" />
								<br /><label for="confirm">Confirm Password</label>
								<br /><input name="confirm" type="password" id="confirm" />
								<br /><input type="submit" name="Submit" value="Change Password" />
							</div>
						</form>
					</fieldset>
				</p>
				
				<p style="font-size: 75%"><span style="color: red">ATTENTION <abbr title="Internet Explorer">IE</abbr> USERS:</span> The navigation menu for the Members Area may not work correctly for some or all versions of Internet Explorer. If you are experiencing problems navigating the Members Area, you may want to <a href="http://www.microsoft.com/windows/internet-explorer/worldwide-sites.aspx">update your browser</a>. You may also want to download and install an alternative internet browser, such as <a href="http://www.google.com/chrome">Google Chrome</a>, <a href="http://www.mozilla.com/en-US/">Mozilla Firefox</a>, <a href="http://www.apple.com/safari/">Safari</a> or <a href="http://www.opera.com/">Opera</a>. If problems persist, navigate the Members Area using the <a href="members/sitemap.php">sitemap</a>.</p>
				
			</div>
			
			<!-- Bottom Navigation Tray -->
			<?php echo $canvas['tray'] . "\r"; ?>
			
			<!-- Footnotes -->
			<?php include "footnotes.php"; ?>
			
		</div>
	</body>
	
</html>
<?php /* Close connection to the MySQL Database */ mysql_close($connection); ?>
