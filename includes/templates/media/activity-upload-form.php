<?php

/**
 * BuddyPress - Media Activity Upload Form
 *
 * This template has AJAX functions to attach images to activity items
 *
 */

?>

<div id="plupload-upload-ui" class="hide-if-no-js">
	<div id="drag-drop-area">
		<div class="drag-drop-inside">
			<p class="drag-drop-info"><?php _e('Drop files here'); ?></p>
			<p><?php _ex('or', 'Uploader: Drop files here - or - Select Files'); ?></p>
			<p class="drag-drop-buttons"><input id="plupload-browse-button" type="button" value="<?php esc_attr_e('Select Files'); ?>" class="button" /></p>
		</div>
	</div>
	<div id="percentage"></div>
</div>

<?php

$variables = bp_action_variables();

$plupload_init = array(
'runtimes'            => 'html5,silverlight,flash,html4',
'browse_button'       => 'plupload-browse-button',
'container'           => 'plupload-upload-ui',
'drop_element'        => 'drag-drop-area',
'file_data_name'      => 'async-upload',            
'multiple_queues'     => true,
'max_file_size'       => wp_max_upload_size().'b',
'url'                 => admin_url('admin-ajax.php'),
'flash_swf_url'       => includes_url('js/plupload/plupload.flash.swf'),
'silverlight_xap_url' => includes_url('js/plupload/plupload.silverlight.xap'),
'filters'             => array(array('title' => __('Allowed Files'), 'extensions' => '*')),
'multipart'           => true,
'urlstream_upload'    => true,

// additional post data to send to our ajax hook
'multipart_params'    => array(
  '_ajax_nonce' => wp_create_nonce('photo-upload'),
  'action'      => 'bp_media_photo_activity_attach',            // the ajax action name
  'user_id' => bp_loggedin_user_id(),
),
);

// we should probably not apply this filter, plugins may expect wp's media uploader...
$plupload_init = apply_filters('plupload_init', $plupload_init); ?>

<script type="text/javascript">

jQuery(document).ready(function($){

	var $plupload_init = <?php echo json_encode($plupload_init); ?>;

	// create the uploader and pass the config from above
	var uploader = new plupload.Uploader($plupload_init);
	
	//console.log( $plupload_init );
	
	// checks if browser supports drag and drop upload, makes some css adjustments if necessary
	uploader.bind('Init', function(up){
			
		var uploaddiv = $('#plupload-upload-ui');
		
		if(up.features.dragdrop){
		  uploaddiv.addClass('drag-drop');
		    $('#drag-drop-area')
		      .bind('dragover.wp-uploader', function(){ uploaddiv.addClass('drag-over'); })
		      .bind('dragleave.wp-uploader, drop.wp-uploader', function(){ uploaddiv.removeClass('drag-over'); });
		
		}else{
		  uploaddiv.removeClass('drag-drop');
		  $('#drag-drop-area').unbind('.wp-uploader');
		}
	});

  uploader.init();
  
	uploader.bind('BeforeUpload', function(up, file) {
		 
	    up.settings.multipart_params['description'] = $('#image-description').val();
	    
		console.log( up.settings.multipart_params );
	});
	
	// a file was added in the queue
	uploader.bind('FilesAdded', function(up, files){
		
		var hundredmb = 100 * 1024 * 1024, max = parseInt(up.settings.max_file_size, 10);
		
		plupload.each(files, function(file){
		  if (max > hundredmb && file.size > hundredmb && up.runtime != 'html5'){
		    // file size error?
		
		  }else{
		  	
		    // a file was added, you may want to update your DOM here...
		    console.log(file);
		  }
		});
		
		up.refresh();
		up.start();
	});
	
	uploader.bind('UploadProgress', function(up, file) {	        
	    $('#percentage').html('<progress max="100" value="0"></progress>');
	    $('#percentage progress').val(file.percent);
	});
	
	var added_data = Array();
	
	// a file was uploaded 
	uploader.bind('FileUploaded', function(up, file, data) {
	
		// this is your ajax response, update the DOM with it or something...
		console.log(data.response);
		
		$('#percentage').html('');
		
		$('#plupload-upload-ui').append('<img class="media-activity-tmp" src="' + JSON.parse(data.response) + '">');
		
		added_data.push( JSON.parse(data.response) );
		
		$('#bp-media-images').val( added_data );
		
		console.log( added_data );
	
	});

});   

</script>