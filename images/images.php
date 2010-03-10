<?php

	$user = $_SERVER['REMOTE_USER'];
	
	if ($user == '') {
		die('No authorised user.');
	}

	include_once('../config/config.php');
	
	$targetDir = IMAGES . '/' . $user;
	// Create target dir
	if (!file_exists($targetDir))
		@mkdir($targetDir);

	$dir = opendir(IMAGES . '/' . $user);
	
	$prot = 'http' . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 's' : '');
	$host = $_SERVER["HTTP_HOST"];
	$root = substr(
				$_SERVER["SCRIPT_NAME"], 0,
				strrpos(
					$_SERVER["SCRIPT_NAME"], '/',
					0 - strrpos(
						$_SERVER["SCRIPT_NAME"], '/'
					)
				)
			);
	
	while (($file = readdir($dir)) !== false) {
		$filetype = substr($file, -3);
		if (preg_match('/(png|gif|jpe?g)/i', $filetype)) {
			$imageurl = $prot . '://' . $host . $root . '/image.php?user=' . $user . '&amp;image=' . $file;
			echo	'<li><div class="image">' .
					'<a class="imagedel" href="?action=del&image='.$file.'"></a>' .
					'<a rel="userimages" class="imagelink" href="'.$imageurl.'&size=580" title="'.htmlentities($imageurl).'">' .
					'<img src="', $imageurl, '&size=96" />' .
					'</a>' .
					'</div>' .
					'<input type="text" class="imagelink" value="',$imageurl,'" /></li>';
		}
	}
	
?>
