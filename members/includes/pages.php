<?php
	
	/* Display Page Numbers below Tables */
	
	// Get URL of Page
	chdir($current_directory);
	$url = $_SERVER["SCRIPT_NAME"];
	chdir('/home/dulynote/public_html/members/includes/');
	
	// Determine Current Position in Array of Pages
	$middle = ceil($width / 2);
	$current_page = $page;
	$previous_page = $current_page - 1;
	$next_page = $current_page + 1;
	$last_page = ceil($num_rows / $limit) + 1;
	$last_page_number = $last_page - 1;
	$first = $start + 1;
	$last = ($start + $limit) * ($num_rows >= $start + $limit) + $num_rows * ($num_rows < $start + $limit);
	
	if ($id == 0 && $query == '' && $school == '') {
		
		// Show range of data
		if ($num_rows > $limit) { ?><span style="float:right"><small>Displaying items <?php echo $first . "-" . $last . " of " . $num_rows; ?></small></span><?php }
		
		// Don't Display Arrows if there aren't enough pages
		if ($num_rows > $limit && $last_page <= $width) showPages(1, $last_page, $current_page, $url);
		
		// Display Arrows if there are enough pages
		else if ($num_rows > $limit && $last_page > $width) {
			
			/* << */ if ($current_page > 2) { ?><span class="boxed"><a title="First Page" href="<?php echo $url; ?>?page=1">&lt;&lt;</a></span> <?php }
			/* < */ if ($current_page > 1) { ?><span class="boxed"><a title="Previous Page" href="<?php echo $url; ?>?page=<?php echo $previous_page; ?>">&lt;</a></span> <?php }
			
			// Display Page Numbers based on Current Position in Array of Pages
			if ($current_page < $middle) showPages(1, $width + 1, $current_page, $url);
			else if ($current_page <= $last_page - $middle && $current_page >= $middle) showPages($current_page - $middle + 1, $current_page + $middle, $current_page, $url);
			else if ($current_page > $last_page - $middle && $current_page >= $middle) showPages($last_page - $width, $last_page, $current_page, $url);
			
			/* > */ if ($current_page < $last_page - 1) { ?><span class="boxed"><a title="Next Page" href="<?php echo $url; ?>?page=<?php echo $next_page; ?>">&gt;</a></span> <?php }
			/* >> */ if ($current_page < $last_page - 2) { ?><span class="boxed"><a title="Last Page" href="<?php echo $url; ?>?page=<?php echo $last_page_number; ?>">&gt;&gt;</a></span> <?php }
			
		}
		
	// Or Display "View All"
	} else { ?><span class="boxed"><a href="<?php echo $url; ?>">View All</a></span><?php }
	
	echo "<br />\r";
	
	// Display Page Numbers
	function showPages($begin, $end, $current_page, $url) {
		for ($page_number = $begin; $page_number < $end; $page_number++) {
			if ($page_number != $current_page) { ?><span class="boxed"><a title="Page <?php echo $page_number; ?>" href="<?php echo $url; ?>?page=<?php echo $page_number; ?>"><?php echo $page_number; ?></a></span> <? }
			else { ?><a title="Page <?php echo $page_number; ?>" style="font-size: 85%; margin-top: 5px; font-weight: bold"><?php echo $page_number; ?></a> <?php }
		}
	}
	
?>
