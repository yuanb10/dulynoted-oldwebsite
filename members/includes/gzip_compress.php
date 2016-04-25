<?php
	
	/* Enable GZIP Compression */
	
	// Save Current Working Directory
	$current_directory = getcwd();
	chdir('/home/dulynote/public_html/members/includes/');
	
	// GZIP Compress Page
	if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler");
	else ob_start();
	
?>
