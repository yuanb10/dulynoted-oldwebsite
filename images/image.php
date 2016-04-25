<?php
	
	/* Add "DulyNotedRPI.com" watermark to Bottom Right corner of an image */
	
	// Get source of image file from URL
	$source = $_GET['src'];
	
	// Display "NO IMAGE AVAILABLE" if image does not exist
	if (!file_exists($source)) $image = imagecreatefromjpeg('members/unknown.jpg');
	else {
		$image_size = getimagesize($source);
		$image_type = $image_size['mime'];
		if ($image_type == "image/gif") $image = imagecreatefromgif($source);
		else if ($image_type == "image/jpeg") $image = imagecreatefromjpeg($source);
		else if ($image_type == "image/png") $image = imagecreatefrompng($source);
	}
	
	// Tell browser this is an image
	header("Content-type: " . $image_type);
	
	// Setup "DulyNotedRPI.com" Watermark
	$watermark = imagecreatefromgif('watermark.gif');
	$image_width = imagesx($image);
	$image_height = imagesy($image);
	$watermark_width = imagesx($watermark);
	$watermark_height = imagesy($watermark);
	$width = $image_width - $watermark_width - 1;
	$height = $image_height - $watermark_height;
	imagecopymerge($image, $watermark, $width, $height, 0, 0, $watermark_width, $watermark_height, 50);
	
	// Display image with watermark on bottom right corner
	if ($image_type == "image/gif") imagegif($image);
	else if ($image_type == "image/jpeg") imagejpeg($image);
	else if ($image_type == "image/png") imagepng($image);
	imagedestroy($image);
	imagedestroy($watermark);
	
?>
