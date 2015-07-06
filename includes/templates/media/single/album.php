<?php

$variables = bp_action_variables();


if( $variables[0] === 'photo' ) {

	bp_media_get_template_part( 'single/photo');
	
} else {

?>
	
	<?php if( is_user_logged_in() ) : ?>
	
	<p>
		<div id="bp-media-add-photo" class="generic-button"><a><?php _e( 'Add Photos', 'bp_album' ); ?></a></div>
	</p>
		
	<? bp_media_get_template_part( 'upload-form' ); ?>
	
	<?php endif ; ?>
	

	<ul id="media-stream" class="media-list grid-list">

	<?php

	add_thickbox();


	$attachments = get_attached_media( 'image', $variables[0] );


	foreach( $attachments as $attachment ) {
	
		$user = get_user_by( 'id', (int) $attachments[$attachment->ID]->post_author );
				
		?>
		
		<a onclick="bp_media_get_image( 'pop', <?php echo $attachment->ID ?>, '<?php echo $attachments[$attachment->ID]->guid ?>', <?php echo $attachments[$attachment->ID]->post_author ?> );" href="#" data-id="<?php echo $attachment->ID; ?>">	
			<div class="media-thumbnail"><?php echo wp_get_attachment_image( $attachment->ID, 'thumbnail' ); ?></div>
		</a>
		
		<div id="pop"></div>

	</ul>
	

		<?php
		
	}
	
}
