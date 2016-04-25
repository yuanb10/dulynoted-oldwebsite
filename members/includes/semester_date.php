<?php
	
	/* Convert Semester to an appropriate Date */
	
	function semesterDate ($semester) {
		$date = substr($semester, -4, 4);
		if (substr($semester, 0 , strlen($semester) - 5) == 'Fall') $date .= '/09/01';
		else $date .= '/02/01';
		return $date;
	}
	
?>
