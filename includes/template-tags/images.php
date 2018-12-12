<?php
/**
 * Functions for displaying images in theme templates.
 *
 * @package SpeedRunner
 * @since 0.2.0
 */

namespace Sup\SpeedRunner\Template;

use function Sup\SpeedRunner\speedrunner;

/**
 * Get an image wrapped in a container with an optional intrinsic ratio.
 *
 * @param int          $attachment_id The attachment ID.
 * @param string       $size          The post thumbnail size.
 * @param string|array $attr          Optional. Query string or array of attributes. Default empty.
 * @return string Image markup wrapped in a ratio container.
 */
function get_wrapped_attachment_image( $attachment_id, $size, $attr = '' ) {
	$html  = '';
	$icon  = false;
	$image = wp_get_attachment_image(
		$attachment_id,
		$size,
		$icon,
		$attr
	);

	if ( $image ) {
		$html = speedrunner()->features()['speedrunner-enable-lazy-loading']->wrap_post_thumbnail( $image, null, $attachment_id, $size, $attr );
	}

	return $html;
}
