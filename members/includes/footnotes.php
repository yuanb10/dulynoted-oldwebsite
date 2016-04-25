<?php
	
	/* Footnotes Found at Very Bottom of Page */
	
	// Get Filename of Current Page from server
	session_start();
	chdir($current_directory);
	$parts = Explode("/", $_SERVER["SCRIPT_NAME"]);
	$_SESSION["page"] = $parts[count($parts) - 1];
	
	// Stop Page Load Timer
	$load_end = microtime();
	$page_load = explode(" ", $load_end);
	$load_end = $page_load[1] + $page_load[0];
	$load_time = $load_end - $load_start;
	$load_time = round ($load_time, 5);
	
?>
<p class="footnote">
				<a href="http://validator.w3.org/check?uri=referer">Valid XHTML 1.1</a> |
				<a href="http://jigsaw.w3.org/css-validator/check/referer">Valid CSS 2.1</a> |
				<a href="http://validator.w3.org/feed/check.cgi?url=http%3A//dulynoted.union.rpi.edu/atom/index.php">Valid Atom 1.0</a> |
				<a href="http://severinghaus.org/projects/icv/?url=http%3A%2F%2Fdulynoted.union.rpi.edu%2Fical%2Findex.php">Valid iCal 2.0</a>
			</p>
			<p class="footnote">
				<a href="http://www.facebook.com/pages/Duly-Noted-All-Male-A-Cappella/98532953439">Facebook</a> |
				<a href="http://www.myspace.com/dulynotedrpi">MySpace</a> |
				<a href="http://www.youtube.com/user/DulyNotedRPI">YouTube</a> |
				<a href="http://www.last.fm/music/duly+noted">Last.fm</a> |
				<a href="http://twitter.com/DulyNotedRPI">Twitter</a> |
				<a href="http://friendfeed.com/dulynotedrpi">Friend Feed</a> |
				<a href="http://www.flickr.com/photos/dulynotedrpi/show/">Flickr</a> |
				<a href="http://www.linkedin.com/groups?gid=1799564">LinkedIn</a> |
				<a href="http://www.ilike.com/artist/Duly+Noted">iLike</a> |
				<a href="http://www.bebo.com/DulyNotedRPI">Bebo</a>
			</p>
			<p class="footnote">
				<span style="font-size: 80%;">
					The Duly Noted All-Male A Cappella Website, Designed and Maintained by <a href="members.php?id=1">Adam Steinberger</a>.<br />
					Creative Commons <a href="http://creativecommons.org/licenses/by-sa/3.0/">Attribution-Share Alike 3.0</a> License January 2009.<br />
					Last modified on <?php /* last modified date */ echo date('d F Y', filemtime($_SESSION['page'])); ?> at <?php /* last modified time */ echo date('g:ia', filemtime($_SESSION['page'])); ?> Eastern Time.<br />
					Viewed on <?php /* current date */ echo date('d F Y'); ?> at <?php /* current time */ echo date('g:ia'); ?>. Loaded in <?php /* page load time */ echo $load_time; ?> seconds.
				</span>
			</p>
			<p class="footnote">
				<a href="/atom/index.php">Atom Feed</a> |
				<a href="/ical/index.php">iCalendar</a> |
				<a href="/vcard/index.php">vCard</a>
			</p>
