<?php

	include_once('config/config.php');
	
	// The file
	$filename = IMAGES . '/' . $_GET['user'] . '/' . $_GET['image'];
	
	//echo $filename, '<br />';

	$ext = substr($filename, -3);

	// Content type
	switch ($ext) {
		case 'png':
		case 'PNG':
			$type = 'image/png';
			break;
		case 'jpg':
		case 'jpeg':
		case 'JPG':
		case 'JPEG':
			$type = 'image/jpeg';
			break;
		case 'gif':
		case 'GIF':
			$type = 'image/gif';
			break;
	}
	header('Content-type: ' . $type);
	
	list($width_orig, $height_orig) = getimagesize($filename);

	if (isset($_GET['size']) && ($_GET['size'] < $width_orig || $_GET['size'] < $height_orig)) {

		// Set a maximum height and width
		$width = $_GET['size'];
		$height = $_GET['size'];
		
		$script_root = substr($_SERVER["SCRIPT_FILENAME"], 0, strrpos($_SERVER["SCRIPT_FILENAME"], '/'));
	
		$cache_file_name = "$script_root/imagecache/" . md5($filename . $width) . ".$ext";
	
		if (!file_exists($cache_file_name)) {
	
			// Get new dimensions
			$ratio_orig = $width_orig/$height_orig;
	
			if ($width/$height > $ratio_orig) {
			   $width = $height*$ratio_orig;
			} else {
			   $height = $width/$ratio_orig;
			}
	
			// Resample
			$image_p = imagecreatetruecolor($width, $height);
			switch ($ext) {
				case 'png':
				case 'PNG':
					$image = imagecreatefrompng($filename);
					imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
					imagepng($image_p, $cache_file_name, 5);
					break;
				case 'jpg':
				case 'jpeg':
				case 'JPG':
				case 'JPEG':
					$image = imagecreatefromjpeg($filename);
					imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
					imagejpeg($image_p, $cache_file_name, 95);
					break;
				case 'gif':
				case 'GIF':
					$image = imagecreatefromgif($filename);
					imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
					imagegif($image_p, $cache_file_name);
					break;
			}

	
		}

		echo file_get_contents($cache_file_name);
	}
	else {
		echo file_get_contents($filename);
	}
?>
