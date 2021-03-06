$(function() {
	function update_images() {
		$.get('images.php', function(data) {
			$('#images').html(data);
			$('li .image img').each(function (){
				$(this).load(function() {
					height = $(this).height();
					margin = (96 - height) / 2;
					$(this).css('margin-top', margin);
				});
			});
			$('input.imagelink').click(function(){
				$(this).focus();
				$(this).select();
			});
			$('a.imagelink').fancybox({
				'titlePosition': 'inside',
				'titleFormat': formatTitle
			});
			$('.imagedel').click(function() {
				return confirm('Are you sure you wish to delete this image? This cannot be undone.');
			});
		});
	}
	
	function formatTitle(title, currentArray, currentIndex, currentOpts) {
		return '<a href="'+title+'">Original</a> | <a href="'+title+'&size=600">Large (600px)</a> | <a href="'+title+'&size=300">Medium (300px)</a> | <a href="'+title+'&size=100">Thumb (100px)</a>';
	}
	
	var uploader = new plupload.Uploader({
		runtimes : 'gears,html5,flash,silverlight',
		browse_button : 'pickfiles',
		container : 'container',
		max_file_size : '5mb',
		url : 'upload.php',
		flash_swf_url : 'lib/js/plupload/plupload.flash.swf',
		silverlight_xap_url : 'lib/js/plupload/plupload.silverlight.xap',
		filters : [
			{title : "Image files", extensions : "jpg,gif,png"}
		]
	});

	uploader.bind('Init', function(up, params) {
		$('#info').html("<div>Current runtime: " + params.runtime + "</div>");
	});

	uploader.bind('FilesAdded', function(up, files) {
		$.each(files, function(i, file) {
			$('#files').append(
				'<li id="' + file.id + '">' +
				'File: ' + file.name + ' (' + plupload.formatSize(file.size) + ') <span class="progress"></span>' +
				'</li>'
			);
		});
	});

	uploader.bind('UploadProgress', function(up, file) {
		$('#' + file.id + " .progress").html(file.percent + "%");
	});
	
	uploader.bind('FileUploaded', function (up, file, response) {
		update_images();
		$('#'+file.id).hide();
	});

	$('#uploadfiles').click(function(e) {
		uploader.start();
		e.preventDefault();
	});

	uploader.init();
	update_images();
});

