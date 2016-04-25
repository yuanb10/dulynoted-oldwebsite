<?php /* Enable GZIP Compression */ include "/home/dulynote/public_html/members/includes/gzip_compress.php"; ?>
<?php header("Content-type: text/x-vcard"); ?>
<?php /* last modified */ $last_modified = date('c', filemtime('/home/dulynote/public_html/vcard/index.php')); ?>
BEGIN:VCARD
VERSION:3.0
N:Duly Noted
FN:Duly Noted A Cappella
ORG:Rensselaer Polytechnic Institute
EMAIL:DulyNotedRPI@gmail.com
TEL:(518) 285-0732
ADR:Duly Noted A Cappella;The Rensselaer Union;110 8th Street;Troy;New York;12180-3599;United States of America
LABEL:Duly Noted A Cappella\nThe Rensselaer Union\n110 8th Street\nTroy, New York 12180-3599\nUnited States of America
URL:http://dulynoted.union.rpi.edu/
REV:<?php echo substr($last_modified, 0, 4) . substr($last_modified, 5, 2) . substr($last_modified, 8, 2) . 'T' . substr($last_modified, 11, 2) . substr($last_modified, 14, 2) . substr($last_modified, 17, 2) . "Z\r"; ?>
END:VCARD
