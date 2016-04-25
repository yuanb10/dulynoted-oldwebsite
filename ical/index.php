<?php /* Enable GZIP Compression */ include "/home/dulynote/public_html/members/includes/gzip_compress.php"; ?>
<?php
	
	/* Public iCalendar */
	
	// Connect to MySQL Database on the PHP server
	session_start();
	include "connect.php";
	
	// Tell browser this is an iCalendar document
	header("Content-type: text/calendar");
	
	// Set Timezone to US/Eastern
	date_default_timezone_set('America/New_York');
	
	// Search for Newest 50 Events that haven't been deleted
	$result = mysql_query("SELECT id, dtstamp, dtstart, dtend, sequence, summary, location, cost FROM events ORDER BY dtstart DESC LIMIT 50");
	
?>
BEGIN:VCALENDAR
PRODID:-//Duly Noted//CalDAV Client//EN
VERSION:2.0
CALSCALE:GREGORIAN
METHOD:PUBLISH
X-WR-CALNAME:Duly Noted A Cappella
X-WR-CALDESC:The Duly Noted A Cappella Events Calendar
X-WR-TIMEZONE:America/New_York

BEGIN:VTIMEZONE
TZID:America/New_York
X-LIC-LOCATION:America/New_York
BEGIN:DAYLIGHT
TZOFFSETFROM:-0500
TZOFFSETTO:-0400
TZNAME:EDT
DTSTART:19700308T020000
RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=2SU
END:DAYLIGHT
BEGIN:STANDARD
TZOFFSETFROM:-0400
TZOFFSETTO:-0500
TZNAME:EST
DTSTART:19701101T020000
RRULE:FREQ=YEARLY;BYMONTH=11;BYDAY=1SU
END:STANDARD
END:VTIMEZONE

<?php /* Enter Event Data */ while ($row = mysql_fetch_array($result)){ ?>
BEGIN:VEVENT
DTSTAMP:<?php /* timestamp */ echo gmdate("Ymd\THis\Z", mktime(substr($row['dtstamp'], 11, 2), substr($row['dtstamp'], 14, 2), substr($row['dtstamp'], 17, 2), substr($row['dtstamp'], 5, 2), substr($row['dtstamp'], 8, 2), substr($row['dtstamp'], 0, 4))); ?>

SEQUENCE:<?php /* edit count */ echo $row['sequence']; ?>

UID:event<?php echo $row['id']; ?>@dulynotedrpi.com
<?php if ($row['cancelled']) { ?>
METHOD:CANCEL
STATUS:CANCELLED
<?php } else{ ?>
ORGANIZER:MAILTO:DulyNotedRPI@gmail.com
SUMMARY:<?php /* event */ echo $row['summary']; ?>

LOCATION:<?php /* location */ echo strip_tags($row['location']); ?>

DESCRIPTION:<?php /* cost */ echo $row['cost']; ?>

DTSTART:<?php /* start */ echo gmdate("Ymd\THis\Z", mktime(substr($row['dtstart'], 11, 2), substr($row['dtstart'], 14, 2), substr($row['dtstart'], 17, 2), substr($row['dtstart'], 5, 2), substr($row['dtstart'], 8, 2), substr($row['dtstart'], 0, 4))); ?>

DTEND:<?php /* end */ echo gmdate("Ymd\THis\Z", mktime(substr($row['dtend'], 11, 2), substr($row['dtend'], 14, 2), substr($row['dtend'], 17, 2), substr($row['dtend'], 5, 2), substr($row['dtend'], 8, 2), substr($row['dtend'], 0, 4))); ?>

<?php } ?>
END:VEVENT

<?php } ?>
END:VCALENDAR
<?php /* Close connection to the MySQL Database */ mysql_close($connection); ?>
