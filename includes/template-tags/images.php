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

	// A default class is set within get_image_wrapper so just pass true if not set.
	$classes   = ( isset( $attr['wrapper'] ) ) ? $attr['wrapper'] : true;
	$has_ratio = ( isset( $attr['ratio'] ) ) ? $attr['ratio'] : false;

	if ( $image ) {
		$html = speedrunner()->features()['speedrunner-enable-lazy-loading']->get_image_wrapper( $image, $attachment_id, $size, $classes, $has_ratio );
	}

	return $html;
}
