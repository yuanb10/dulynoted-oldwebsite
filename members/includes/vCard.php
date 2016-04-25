<?php
	
	/* vCard of our A Cappella Contacts */
	
	// Connect to MySQL Database on the PHP server
	session_start();
	include "connect.php";
	
	// Tell browser this is a vCard document
	header("Content-type: text/x-vcard");
	
	// Search for Contacts that haven't been deleted
	$result = mysql_query("SELECT dtstamp, name, school, email, website FROM contacts WHERE deleted=0 ORDER BY name");
	
	// Enter Contact Data
	while ($row = mysql_fetch_array($result))
{ ?>BEGIN:VCARD
VERSION:3.0
N:<?php /* name */ echo $row['name']; ?>

FN:<?php /* name */ echo $row['name']; ?>

ORG:<?php /* long name */ echo $row['school'] . " " . $row['name']; ?>

EMAIL:<?php /* email */ echo $row['email']; ?>

URL:<?php /* website */ echo $row['website']; ?>

REV:<?php /* timestamp */ echo substr($row['dtstamp'], 0, 4) . substr($row['dtstamp'], 5, 2) . substr($row['dtstamp'], 8, 2) . 'T' . substr($row['dtstamp'], 11, 2) . substr($row['dtstamp'], 14, 2) . substr($row['dtstamp'], 17, 2) . 'Z'; ?>

END:VCARD

<?php } ?>
<?php /* Close connection to the MySQL Database */ mysql_close($connection); ?>
