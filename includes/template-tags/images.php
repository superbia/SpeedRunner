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
 * Get an image wrapped in a container with an intrinsic ratio.
 *
 * @param int          $attachment_id The attachment ID.
 * @param string       $size          The post thumbnail size.
 * @param string|array $attr          Optional. Query string or array of attributes. Default empty.
 * @return string Image markup wrapped in a ratio container.
 */
function get_attachment_ratio_image( $attachment_id, $size, $attr = '' ) {
	$html  = '';
	$icon  = false;
	$image = wp_get_attachment_image(
		$attachment_id,
		$size,
		$icon,
		$attr
	);

	// A default class is set within get_ratio_container so just pass true if not set.
	$ratio_class = ( isset( $attr['ratio_container'] ) ) ? $attr['ratio_container'] : true;

	if ( $image ) {
		$html = speedrunner()->features()['speedrunner-enable-lazy-loading']->get_ratio_container( $image, $attachment_id, $size, $ratio_class );
	}

	return $html;
}
