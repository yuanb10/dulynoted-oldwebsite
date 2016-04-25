<?php
	
	/* Format Datetime for the MySQL Database */
	
	function databaseTime($time) {
		if (substr($time, 0, 2) == '12' && substr($time, 6, 2) == 'AM') $time = "00" . substr($time, 2, 3) . ":00";
		else if (substr($time, 6, 2) == 'AM' || (substr($time, 0, 2) == '12' && substr($time, 6, 2) == 'PM')) $time = substr($time, 0, 5) . ":00";
		else if (substr($time, 6, 2) == 'PM') $time = (substr($time, 0 , 2) + 12) . substr($time, 2, 3) . ":00";
		return $time;
	}
	
?>
