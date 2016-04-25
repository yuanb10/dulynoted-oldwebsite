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
		<title>View the Weekly Schedule - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
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
				<h1>The Weekly Schedule</h1>
				<table class="dynamic_table" id="schedule_app">
					<thead>
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
							
						// Declare variables for data manipulation
						$index = 0;
						$username = $_SESSION['username'];
						for ($hour = 8; $hour < 24; $hour++) for ($day = 1; $day < 8; $day++) $schedule[$day][$hour] = '0';
						
						// Search for and fetch Rehearsal Times from the MySQL Database
						$result2 = mysql_query("SELECT schedule FROM rehearsals");
						$row2 = mysql_fetch_array($result2);
						
						// Search for and fetch Member Schedules from the MySQL Database
						$result = mysql_query("SELECT firstname, lastname, schedule FROM members WHERE position!='Alumnus' && position!='Inactive' && studying='On Campus' ORDER BY lastname");
						while ($row = mysql_fetch_array($result)) for ($hour = 8; $hour < 24; $hour++) for ($day = 1; $day < 8; $day++) if (strpos($row['schedule'], 'd' . $day . 'h' . $hour)) { $schedule[$day][$hour]++; $members[$day][$hour] .= $row['firstname'] . ' ' . $row['lastname'] . ', '; }
						for ($hour = 8; $hour < 24; $hour++) for ($day = 1; $day < 8; $day++) $schedule_modified[$index++] = $schedule[$day][$hour];
						sort($schedule_modified);
							
						// Display Combined Weekly Schedule
						for ($hour = 8; $hour < 24; $hour++) {
							$hour_mod = $hour - 12 * ($hour > 12);
							if ($hour_mod < 10) $hour_mod = '0' . $hour_mod;
							echo "<tr class=\"centered\">\n\t\t\t\t\t\t";
							echo "	<td>" . $hour_mod . "</td>\n\t\t\t\t\t\t";
							for ($day = 1; $day < 8; $day++) {
								echo "	<td ";
								if (strpos($row2['schedule'], 'd' . $day . 'h' . $hour)) echo "class=\"rehearsal\"";
								else if ($schedule[$day][$hour] > 0) echo "class=\"busy" . $schedule[$day][$hour] . "\"";
								else echo "class=\"free\"";
								echo " id=\"d" . $day . "h" . $hour . "\" onclick=\"rehearsalMod(" . $day . "," . $hour . ")\"";
								if ($schedule[$day][$hour] > 0) echo " title=\"" . substr($members[$day][$hour], 0, count($members[$day][$hour]) - 3) . "\"";
								echo ">" . $schedule[$day][$hour] . "</td>\n\t\t\t\t\t\t";
							}
							echo "</tr>\n\t\t\t\t\t\t";
						}
						echo "\r";
						
						?>
					</tbody>
				</table>
				
				<form class="centered" name="editbox" method="post" action="members/actions/update/rehearsals.php">
					<div>
						<input type="hidden" name="schedule" id="schedule" value="<?php echo $row2['schedule']; ?>" />
						<input type="submit" value="Update Rehearsal Times" />
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
