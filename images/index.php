<?php

	$user = $_SERVER['REMOTE_USER'];
	
	if ($user == '') {
		die('No authorised user.');
	}

	include_once('../config/config.php');
	
	$action = (isset($_GET['action']) ? $_GET['action'] : '');
	
	switch ($action) {
		case 'del':
			$imagefile = IMAGES . '/' . $user . '/' . $_GET['image'];
			unlink($imagefile);
			header('Location: index.php');
			break;
		default:
			include('includes/header.html');

			echo '<h3>My Images</h3>';
			
			// by default we need to list all the users images.
			echo '<ul id="images">';			
			echo '</ul>';
			echo '<div id="container">';
			// upload form
			
			echo '<div id="info" style="display: none;">No runtime found.</div>';
			echo '<a class="button" id="pickfiles" href="#">Select files</a>', "\n";
			echo '<a class="button" id="uploadfiles" href="#">Upload files</a>';
			echo '<ul id="files">';			
			echo '</ul>';
			echo '</div>';
			include('includes/footer.html');
	}
	
?>
