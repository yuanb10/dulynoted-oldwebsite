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
		<title>Edit an Attendance Sheet - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
		<base href="http://dulynoted.union.rpi.edu/" />
		<link rel="search" type="application/opensearchdescription+xml" title="Duly Noted" href="search.xml" />
		<link href="atom/index.php" type="application/atom+xml" rel="alternate" title="Duly Noted A Cappella Feed" />
		<link href="style.css" type="text/css" rel="stylesheet" />
		<link href="members/.jquery-ui.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src=".jquery.js"></script>
		<script type="text/javascript" src="script.js"></script>
		<script type="text/javascript" src="members/.jquery-ui.js"></script>
		
	</head>
	
	<body>
		<div class="container">
			
			<!-- Duly Noted Logo -->
			<?php echo $canvas['logo'] . "\r"; ?>
			
			<!-- Navigation Menu -->
			<?php echo $canvas['navbar'] . "\r"; ?>
			
			<!-- Main Content of Page -->
			<div class="canvas">
			
			<script type="text/javascript">
				// Use jQuery UI popup calendar for dates
				$(function() {
					$("#date").datepicker({showOn: 'button', buttonImage: 'members/.images/calendar.gif', buttonImageOnly: true, dateFormat: 'yy-mm-dd'});
				});
			</script>
				
				<?php /* Display Members Area Navigation Menu */ include "menu.php"; ?>
				<?php
					
					// Search for and fetch Contact from the MySQL Database
					$id = $_REQUEST['id'];
					$_SESSION['attendanceid'] = $id;
					$row = mysql_fetch_array(mysql_query("SELECT id, dtstamp, ontime, late, absent, excused FROM attendance WHERE id='$id'"));
					
				?>
				<h1>Edit Attendance Sheet</h1>
				<form name="editbox" method="post" action="members/actions/update/attendance.php">
					<div>
						<input type="text" name="date" id="date" value="<?php echo $row['dtstamp']; ?>" />
						<div class="shaded_div">
							<?php
							
							// Store Attendance Type Sizes in Array
							$sizes = array(count(explode(" ", $row['ontime'])) - (array_sum(explode(" ", $row['ontime'])) == 0), count(explode(" ", $row['late'])) - (array_sum(explode(" ", $row['late'])) == 0), count(explode(" ", $row['absent'])) - (array_sum(explode(" ", $row['absent'])) == 0), count(explode(" ", $row['excused'])) - (array_sum(explode(" ", $row['excused'])) == 0));
							
							// Store Attendance in 2D Array
							$attendance[0] = explode(" ", $row['ontime']);
							$attendance[1] = explode(" ", $row['late']);
							$attendance[2] = explode(" ", $row['absent']);
							$attendance[3] = explode(" ", $row['excused']);
							
							// Fetch Attendance from the MySQL Database
							for ($index = 0; $index < 4; $index++) for ($size = 0; $size < $sizes[$index]; $size++)
					{ ?><div>
								<label for="member<?php echo $attendance[$index][$size]; ?>"><a href="members.php?id=<?php echo $attendance[$index][$size]; ?>"><?php echo $solo[$attendance[$index][$size]]; ?></a></label>
								<span style="float:right">
									<input type="radio" name="member<?php echo $attendance[$index][$size]; ?>" id="member<?php echo $row['id']; ?>" value="on time" <?php if ($index == 0) { ?>checked="checked"<?php } ?> /> On Time
									<input type="radio" name="member<?php echo $attendance[$index][$size]; ?>" id="member<?php echo $row['id']; ?>" value="late" <?php if ($index == 1) { ?>checked="checked"<?php } ?> /> Late
									<input type="radio" name="member<?php echo $attendance[$index][$size]; ?>" id="member<?php echo $row['id']; ?>" value="absent" <?php if ($index == 2) { ?>checked="checked"<?php } ?> /> Absent
									<input type="radio" name="member<?php echo $attendance[$index][$size]; ?>" id="member<?php echo $row['id']; ?>" value="excused" <?php if ($index == 3) { ?>checked="checked"<?php } ?> /> Excused
								</span>
							</div>
							<?php } echo "\r"; ?>
						</div>
						<br />
						<input type="submit" name="submit" value="Update Attendance" style="display:block;margin:0 auto;" />
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
