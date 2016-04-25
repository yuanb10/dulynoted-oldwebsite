<?php
	
	/* Calculate Current Budget */
	
	// Setup Variables for Data Manipulation
	$balance[$index = 0] = 0;
	setlocale(LC_MONETARY, "en_US");
	
	// Search for and fetch Transaction(s) from the MySQL Database
	$result = mysql_query("SELECT id, cost FROM budget WHERE deleted=0 ORDER BY date ASC");
	
	// Sum up all Transactions
	while ($row = mysql_fetch_array($result)) {
		$balance[$row['id']] = $balance[$index] + $row['cost'];
		$index = $row['id'];
	}
	
?>
