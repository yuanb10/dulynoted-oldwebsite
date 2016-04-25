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
		<title>The A Cappella Contact List - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
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
				<h1>The A Cappella Contact List</h1>
				<div class="centered" style="margin-bottom:5px;">
					<?php for ($letter = 0; $letter < 26; $letter++) { ?><span class="boxed"><a href="members/contacts.php?query=<?php echo substr('ABCDEFGHIJKLMNOPQRSTUVWXYZ', $letter, 1); ?>"><?php echo substr('ABCDEFGHIJKLMNOPQRSTUVWXYZ', $letter, 1); ?></a></span>
					<?php } echo "\r"; ?>
				</div>
				<div class="shaded_div">
					<?php
					
					// Obtain page # or item ID from URL
					$query = $_REQUEST['query'];
					$page = $_REQUEST['page'];
					$id = $_REQUEST['id'];
					if ($page < 1) $page = 1;
					if ($id < 1) $id = 0;
					
					// Set table size and scroll width
					list ($limit, $width) = array (25, 7);
					
					// Determine position of first (top) item on table
					$num_rows = mysql_num_rows(mysql_query("SELECT id FROM contacts"));
					if ($page > $num_rows / $limit) $page = ceil($num_rows / $limit);
					$start = $limit * ($page - 1);
					$width += (floor($width / 2) == $width / 2);
					
					// Search for and fetch Contact(s) from the MySQL Database
					if ($query != '') $result = mysql_query("SELECT id, name, school, website, email  FROM contacts WHERE name REGEXP '$query' ORDER BY name ASC");
					else if ($id > 0) $result = mysql_query("SELECT id, name, school, website, email FROM contacts WHERE id='$id'");
					else $result = mysql_query("SELECT id, name, school, website, email FROM contacts ORDER BY deleted, name LIMIT $start,$limit");
					
					// Insert Contact data into table
					while ($row = mysql_fetch_array($result))
			{ ?><div<?php if ($row['deleted']) { ?> style="font-size: 75%"<?php } ?>>
						<span style="float:right">
							<?php if (!$row['deleted']) { ?><a href="members/edit/contact.php?id=<?php echo $row['id']; ?>">Edit</a> /
							<a href="members/actions/delete/contact.php?id=<?php echo $row['id']; ?>">Delete</a>
							<?php } else { ?><a href="members/actions/restore/contact.php?id=<?php echo $row['id']; ?>">Restore</a><?php } ?>
						</span>
						<a href="members/contacts.php?id=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a>
						from <a href="http://www.google.com/search?q=<?php echo str_ireplace(' ', '+', $row['school']); ?>"><?php echo $row['school']; ?></a>
						<br /><a href="<?php echo $row['website']; ?>">Website</a> /
						<a href="mailto:<?php echo $row['email']; ?>"><?php echo $row['email']; ?></a>
					</div>
					<?php } echo "\r"; ?>
				</div>
				<?php /* Page Navigation */ include "pages.php"; ?>
				
				<p>
					<form name="editbox" method="post" action="members/actions/new/contact.php">
						<div>
							<div class="input_box_thin">
								<label for="name">Group Name</label><br />
								<input type="text" name="name" id="name" maxlength="64" />
							</div>
							<div class="input_box_thin">
								<label for="school">School</label><br />
								<input type="text" name="school" id="school" maxlength="64" />
							</div>
							<div class="input_box_thin">
								<label for="website">Website</label><br />
								<input type="text" name="website" id="website" maxlength="128" />
							</div>
							<div class="input_box_thin">
								<label for="email">Email Address</label><br />
								<input type="text" name="email" id="email" maxlength="64" />
							</div>
							<input style="display:block; clear:both; margin:0px auto;" type="submit" name="submit" value="Add Contact" />
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
