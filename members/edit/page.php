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
		<title>Edit a Webpage - Duly Noted All-Male A Cappella - RPI Troy, NY</title>
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
					
					// Search for and fetch Page from the MySQL Database
					$id = $_REQUEST['id'];
					$_SESSION['pageid'] = $id;
					$row = mysql_fetch_array(mysql_query("SELECT page, title, item, heading, description, detail, content, note, locked FROM pages WHERE id='$id'"));
					
				?>
				<h1>Edit <em><a href="<?php echo $row['page']; ?>.php"><?php echo $row['title']; ?></a></em></h1>
				<form name="editbox" method="post" action="members/actions/update/page.php">
					<div>
						<input type="hidden" name="page" id="page" maxlength="32" value="<?php echo $row['page']; ?>" />
						<label for="title">Title</label>
						<br /><input type="text" name="title" id="title" maxlength="64" value="<?php echo $row['title']; ?>" />
						<br /><label for="item">Item Title</label>
						<br /><input type="text" name="item" id="item" maxlength="255" value="<?php echo htmlentities($row['item']); ?>" style="width: 550px" />
						<br /><label for="heading">Heading</label>
						<br /><input type="text" name="heading" id="heading" maxlength="64" value="<?php echo $row['heading']; ?>" />
						<br /><label for="description">Description</label>
						<br /><input type="text" name="description" id="description" maxlength="255" value="<?php echo $row['description']; ?>" style="width: 550px" />
						<br /><label for="detail">Details</label>
						<br /><input type="text" name="detail" id="detail" maxlength="500" value="<?php echo htmlentities($row['detail']); ?>" style="width: 550px" />
						<?php if (!$row['locked']) { ?><br /><label for="content">Content</label><br /><textarea name="content" id="content" style="width:764px;height:100px"><?php echo htmlspecialchars($row['content']); ?></textarea><?php } ?>
						<br /><label for="note">Note</label>
						<br /><input type="text" name="note" id="note" maxlength="255" value="<?php echo htmlspecialchars($row['note']); ?>" style="width: 550px" /><br />
						<input type="submit" name="submit" value="Update" style="display:block;margin:0 auto;" />
					</div>
				</form>
				
			</div>
			
			<!-- Bottom Navigation Tray -->
			<?php echo $canvas['tray']; ?>
			
			<!-- Footnotes -->
			<?php include "footnotes.php"; ?>
			
		</div>
	</body>
	
</html>
<?php /* Close connection to the MySQL Database */ mysql_close($connection); ?>
