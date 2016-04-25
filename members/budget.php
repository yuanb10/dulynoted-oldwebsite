<?php /* Enable GZIP Compression */ include "/home/dulynote/public_html/members/includes/gzip_compress.php"; ?>
<?php /* Connect to MySQL Database on the PHP server */ session_start(); include "connect.php"; ?>
<?php /* Get Canvas Elements */ $canvas = mysql_fetch_array(mysql_query("SELECT logo, navbar, tray FROM canvas WHERE id=1")); ?>
<?php /* Authorize Member and Verify if Logged-In */ include "auth.php"; ?>
<?php /* Authorize Member and Verify if Officer */ include "officer_auth.php"; ?>
<?php /* Calculate Budget */ include "calculate_budget.php"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	
	<head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="robots" content="noindex, nofollow" />
		<title>Manage the Budget - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
		<base href="http://dulynoted.union.rpi.edu/" />
		<link rel="search" type="application/opensearchdescription+xml" title="Duly Noted" href="search.xml" />
		<link href="atom/index.php" type="application/atom+xml" rel="alternate" title="Duly Noted A Cappella Feed" />
		<link href="style.css" type="text/css" rel="stylesheet" />
		<link href="members/.jquery-ui.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src=".jquery.js"></script>
		<script type="text/javascript" src="script.js"></script>
		<script type="text/javascript" src="members/.jquery-ui.js"></script>
		
		<script type="text/javascript">
			// Confirm Transaction Deletion
			function confirm_delete(id) {
				var box = confirm("Do you really want to PERMANENTLY DELETE this transaction?");
				if (box == true) window.location = "http://dulynoted.union.rpi.edu/members/actions/delete/transaction.php?id=".concat(id);
			}
		</script>
		
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
				<h1>Manage the Budget</h1>
				<h2>Current Balance: <?php echo money_format("%n", $balance[$index]); ?></h2>
				<table class="shaded_table">
					<thead>
						<tr>
							<th>Date</th>
							<th>Description</th>
							<th>Receive</th>
							<th>Spend</th>
							<th>Balance</th>
							<th>Delete</th>
						</tr>
					</thead>
					<tbody>
						<?php
						
						// Obtain page # or item ID from URL
						$page = $_REQUEST['page'];
						$id = $_REQUEST['id'];
						if ($page < 1) $page = 1;
						if ($id < 1) $id = 0;
						
						// Set table size and scroll width
						list ($limit, $width) = array (30, 7);
						
						// Determine position of first (top) item on table
						$num_rows = mysql_num_rows(mysql_query("SELECT id FROM budget WHERE deleted=0"));
						if ($page > $num_rows / $limit) $page = ceil($num_rows / $limit);
						$start = $limit * ($page - 1);
						$width += (floor($width / 2) == $width / 2);
						
						// Search for and fetch Transaction(s) from the MySQL Database
						if ($id > 0) $result = mysql_query("SELECT id, date, description, cost FROM budget WHERE deleted=0 && id='$id'");
						else $result = mysql_query("SELECT id, date, description, cost FROM budget WHERE deleted=0 ORDER BY date DESC LIMIT $start,$limit");
						
						// Insert Budget data into table
						if ($num_rows > 0) {
							while ($row = mysql_fetch_array($result)):
								if ($balance[$row['id']] < 0) $color = "red";
								else $color = "black"; ?>
<tr>
							<td><?php echo $row['date']; ?></td>
							<td><?php echo $row['description']; ?></td>
<?php if ($row['cost'] > 0): ?>
							<td><?php echo money_format("%n", $row['cost']); ?></td>
							<td></td>
<?php elseif ($row['cost'] < 0): ?>
							<td></td>
							<td><?php echo money_format("%n", $row['cost']); ?></td>
<?php	else: ?>
							<td></td>
							<td></td>
<?php endif; ?>
							<td style="color:<?php echo $color; ?>"><em><?php echo money_format("%n", $balance[$row['id']]); ?></em></td>
							<td><a href="members/budget.php?page=<?php echo $page; ?>" onclick="confirm_delete(<?php echo $row['id']; ?>)">Delete</a></td>
						</tr>
						<?php endwhile;
						} else echo "<tr>\n\t\t\t\t\t\t	<td></td>\n\t\t\t\t\t\t	<td> 	Oops! It looks like this transaction doesn't exist...</td>\n\t\t\t\t\t\t	<td></td>\n\t\t\t\t\t\t	<td></td>\n\t\t\t\t\t\t	<td></td>\n\t\t\t\t\t\t	<td></td>\n\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t";
						echo "\r";
						
						?>
					</tbody>
				</table>
				<?php /* Page Navigation */ include "pages.php"; ?>
				
				<form name="editbox" method="post" action="members/actions/new/transaction.php">
					<div>
						<div class="input_box_varsize" style="width:200px">
							<label for="date">Date</label><br />
							<input type="text" name="date" id="date" maxlength="10" />
						</div>
						<div class="input_box_thin">
							<label for="description">Description</label><br />
							<input type="text" name="description" id="description" maxlength="128" />
						</div>
						<div class="input_box_varsize" style="width:60px">
							<label for="type">Type</label><br />
							<select name="type" id="type">
								<option>Spend</option>
								<option>Receive</option>
							</select>
						</div>
						<div class="input_box_thin">
							<label for="amount">Amount ($)</label><br />
							<input type="text" name="amount" id="amount" maxlength="9" />
						</div>
						<input style="display:block; clear:both; margin:0px auto;" type="submit" name="submit" value="Add Transaction" /></td>
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
