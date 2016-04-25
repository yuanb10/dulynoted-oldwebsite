<?php
	
	/* Display Countdown Time until given Date */
	
	function countdown ($start, $end) {
		
		// Make sure given Start and End are valid Datetimes
		if (empty($start)) return "No date provided ";
		if (empty($end) || $end < $start) $end = $start;
		
		// Declare Variables for Data Manipulation
		$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
		$lengths = array("60","60","24","7","4.35","12","10");
		
		// Convert times to Unix standard
		$now = time();
		$unix_start = strtotime($start);
		$unix_end = strtotime($end);
		
		// Double check Unix timestamp
		if (empty($unix_start) || empty($unix_end)) return "Bad date ";
		
		// Determine en-us grammatical syntax
		if ($now > $unix_end) {
			$difference = $now - $unix_end;
			$tense = "since";
		} else if ($now < $unix_start) {
			$difference = $unix_start - $now;
			$tense = "until";
		} else return "Currently in progress ";
		
		// Calculate time difference
		for ($index = 0; $difference >= $lengths[$index] && $j < count($lengths) - 1; $index++) $difference /= $lengths[$index];
		$difference = round ($difference);
		
		// Account for pluralization
		if ($difference != 1) $periods[$index] .= "s";
		
		// Return time until given datetime
		return "$difference $periods[$index] {$tense} ";
		
	}
	
?>
