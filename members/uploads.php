<?php /* Enable GZIP Compression */ include "/home/dulynote/public_html/members/includes/gzip_compress.php"; ?>
<?php /* Connect to MySQL Database on the PHP server */ session_start(); include "connect.php"; ?>
<?php /* Get Canvas Elements */ $canvas = mysql_fetch_array(mysql_query("SELECT logo, navbar, tray FROM canvas WHERE id=1")); ?>
<?php /* Authorize Member and Verify if Logged-In */ include "auth.php"; ?>
<?php /* Authorize Member and Verify if Student */ include "student_auth.php"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	
	<head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="robots" content="noindex, nofollow" />
		<title>Uploads - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
		<base href="http://dulynoted.union.rpi.edu/" />
		<link rel="search" type="application/opensearchdescription+xml" title="Duly Noted" href="search.xml" />
		<link href="atom/index.php" type="application/atom+xml" rel="alternate" title="Duly Noted A Cappella Feed" />
		<link href="style.css" type="text/css" rel="stylesheet" />
		<script type="text/javascript" src=".jquery.js"></script>
		<script type="text/javascript" src="script.js"></script>
		
		<script type="text/javascript">
			// Confirm File Deletion
			function confirm_delete(id) {
				var box = confirm("Do you really want to PERMANENTLY DELETE this file?");
				if (box == true) window.location = "http://dulynoted.union.rpi.edu/members/actions/delete/file.php?id=".concat(id);
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
				
				<?php /* Display Members Area Navigation Menu */ include "menu.php"; ?>
				<h1>Uploads</h1>
				<div class="shaded_div">
					<?php
					
					// Obtain page # or item ID from URL
					$page = $_REQUEST['page'];
					$id = $_REQUEST['id'];
					if ($page < 1) $page = 1;
					if ($id < 1) $id = 0;
					
					// Set table size and scroll width
					list ($limit, $width) = array (20, 7);
					
					// Determine position of first (top) item on table
					$num_rows = mysql_num_rows(mysql_query("SELECT id FROM uploads"));
					if ($page > $num_rows / $limit) $page = ceil($num_rows / $limit);
					$start = $limit * ($page - 1);
					$width += (floor($width / 2) == $width / 2);
					
					// Search for and fetch Upload(s) from the MySQL Database					
					if ($id > 0) $result = mysql_query("SELECT id, filename, description, filesize, date, memberid, locked FROM uploads WHERE id='$id'");
					else $result = mysql_query("SELECT id, filename, description, filesize, date, memberid, locked FROM uploads ORDER BY date DESC, filename LIMIT $start,$limit");
					
					// Insert Upload data into table
					while($row = mysql_fetch_array($result))
			{ ?><div>
						<span style="float:right">
							<?php if (($row['memberid'] == $_SESSION['memberid'] || $_SESSION['isofficer'] == 'yes') && !$row['locked']) { ?><a href="members/uploads.php?page=<?php echo $page; ?>" onclick="confirm_delete(<?php echo $row['id']; ?>)">Delete</a><?php } ?>
							<?php if ($_SESSION['isofficer'] == 'yes' && !$row['locked']) { ?> / <a href="members/actions/lock/file.php?id=<?php echo $row['id']; ?>">Lock</a><?php } ?>
						</span>
						<a href="members/uploads/<?php echo $row['filename']; ?>"><?php echo $row['description']; ?></a>
						<?php if ($row['locked']) { ?><img src="images/icons/lock.gif" alt="Locked Files cannot be deleted" title="Locked Files cannot be deleted" /><?php } ?>
						<?php /* size */ echo round($row['filesize'] / 1024); ?> KB -
						<?php /* timestamp */ echo $row['date']; ?>
					</div>
					<?php } echo "\r"; ?>
				</div>
				<?php /* Page Navigation */ include "pages.php"; ?>
				
				<h2>Upload File</h2>
				<form class="upload" action="members/actions/upload/file.php" method="post" enctype="multipart/form-data">
					<div>
						<span style="color:red;font-size:50%">Please <em>DO NOT</em> upload illegally downloaded or copyright protected files.</span>
						<input type="file" name="file" id="file" />
						<br /><label for="description">File Description</label>
						<br /><input type="text" name="description" id="description" style="width:300px" />
						<br /><input type="submit" name="submit" value="Submit" style="display:block;margin:0 auto;" />
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
