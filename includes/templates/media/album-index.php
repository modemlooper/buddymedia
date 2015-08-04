<?php

/**
 * BuddyPress - Media Stream (Single Item)
 *
 * This template is used by media-loop.php and AJAX functions to show
 * each gallery.
 *
 */

?>

<?php

/**
 * Fires before the display of an media entry.
 *
 */
do_action( 'bp_media_before_entry' ); ?>

<a href="<?php bp_media_album_link(); ?>">
	<li class="<?php bp_media_css_class(); ?>" id="media-<?php bp_media_css_id(); ?>" style="background:url(<?php bp_album_cover_url(); ?>)">
		<div class="album-info">
			<div class="album-title"><?php the_title(); ?></div>
			<div class="album-count"><?php bp_album_image_count(); ?></div>
		</div>
	</li>
</a>