<?php
	
	/* Format Datetime to be Displayed On-Screen */
	
	function displayTime ($time) {
		if ($time < '01:00') $time = "12" . substr($time, 2, 3) . " AM";
		else if ($time < '12:00') $time .= " AM";
		else if ($time >= '12:00' && $time < '13:00') $time .= " PM";
		else if ($time >= '13:00' && $time < '22:00') $time = "0" . (substr($time, 0 , 2) - 12) . substr($time, 2, 3) . " PM";
		else if ($time >= '22:00') $time = (substr($time, 0 , 2) - 12) . substr($time, 2, 3) . " PM";
		return $time;
	}
	
?>
