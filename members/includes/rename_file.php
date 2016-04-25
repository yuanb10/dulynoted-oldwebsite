<?php
	
	/* Rename Non-Unique Filename to Unique Name */
	
	function FileRename($filename) {
		$filerename = explode(".", $filename);
		$filerename[count($filerename) - 2] .= "K";
		$filerename = implode(".", $filerename);
		if (file_exists("/home/dulynote/public_html/members/uploads/" . $filerename)) return FileRename($filerename);
		else return $filerename;
	}
	
?>
