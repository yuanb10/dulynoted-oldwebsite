<?php /* Enable GZIP Compression */ include "/home/dulynote/public_html/members/includes/gzip_compress.php"; ?>
<?php
	
	/* Atom Syndication Feed */
	
	// Connect to MySQL Database on the PHP server
	session_start();
	include "connect.php";
	
	// Search for Newest 20 Posts that haven't been deleted
	$result = mysql_query("SELECT id, title, content, updated FROM posts WHERE deleted=0 ORDER BY updated DESC LIMIT 20");
	
	// Tell browser this is an Atom/XML document
	header("Content-type: application/atom+xml");
	echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n";
	
?>
<feed xmlns="http://www.w3.org/2005/Atom" xml:lang="en-us" xml:base="http://dulynoted.union.rpi.edu/" xmlns:opensearch="http://a9.com/-/spec/opensearch/1.1/">

	<title>Duly Noted All-Male A Cappella</title>
	<link rel="self" href="/atom/index.php" />
	<link rel="current" href="news_feed.php" />
	<updated><?php /* last modified */ echo date("c",filemtime("index.php")); ?></updated>
	<author>
		<name>Duly Noted</name>
		<email>DulyNotedRPI@gmail.com</email>
	</author>
	<id>http://dulynoted.union.rpi.edu/</id>
	<category term="entertainment" />
	<generator uri="atom/index.php" version="2.0">Duly Noted PHP Feed Generator</generator>
	<icon>/images/icons/duly_noted_icon.jpg</icon>
	<logo>/images/duly_noted_logo_small.jpg</logo>
	<rights>Creative Commons Attribution-Share Alike 3.0 United States</rights>
		
<?php /* Enter Post Data */ while ($row = mysql_fetch_array($result)) { ?>
		<entry>
			<title><?php /* title */ echo $row["title"]; ?></title>
			<id>http://dulynoted.union.rpi.edu/news_feed.php?id=<?php echo $row["id"]; ?></id>
			<updated><?php /* timestamp */ echo $row["updated"]; ?></updated>
			<author>
				<name>Duly Noted</name>
				<email>DulyNotedRPI@gmail.com</email>
			</author>
			<content type="xhtml"><div xmlns="http://www.w3.org/1999/xhtml"><?php /* post */ echo $row["content"]; ?></div></content>
			<link rel="alternate" href="http://dulynoted.union.rpi.edu/news_feed.php?id=<?php echo $row['id']; ?>" />
		</entry>
		
<?php } echo "\r"; ?>
</feed>
<?php /* Close connection to the MySQL Database */ mysql_close($connection); ?>
