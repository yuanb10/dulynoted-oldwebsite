<?php
	
	/* Display Time since given Datetime */
	
	function timestamper ($date) {
		
		// Make sure given Datetime is valid
		if (empty($date)) return "Timestamp is unavailable";
		
		// Declare Variables for Data Manipulation
		$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade", "century", "millenium");
		$lengths = array("60","60","24","7","4.35","12","10","10","100");
		
		// Convert times to Unix standard
		$now = time();
		$unix_date = strtotime($date);
		
		// Double check Unix timestamp
		if (empty($unix_date)) return "Invalid timestamp";
		
		// Determine en-us grammatical syntax
		if ($now > $unix_date) $tense = "ago";
		else $tense = "from now";
		$difference = abs($now - $unix_date);
		
		// Calculate time difference
		for ($index = 0; $difference >= $lengths[$index] && $index < count($lengths) - 1; $index++) $difference /= $lengths[$index];
		$difference = round ($difference);
		
		// Account for pluralization
		if ($difference != 1) $periods[$index] .= "s";
		
		// Return time since given datetime
		return "$difference $periods[$index] {$tense}";
		
	}
	
?>
