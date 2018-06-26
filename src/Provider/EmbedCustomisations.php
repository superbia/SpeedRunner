<?php
/**
 * Embed customisations.
 *
 * @package    Blackbird
 * @subpackage Plugin
 * @since 0.2.0
 */

namespace Sup\Blackbird\Provider;

use Sup\Blackbird\HookProviderInterface;
use Sup\Blackbird\PluginAwareInterface;
use Sup\Blackbird\PluginAwareTrait;

/**
 * Embed customisations provider class.
 */
class EmbedCustomisations implements HookProviderInterface, PluginAwareInterface {

	use PluginAwareTrait;

	/**
	 * Register hooks.
	 *
	 * @since 0.1.0
	 */
	public function register_hooks() {
		add_filter( 'oembed_dataparse', array( $this, 'wrap_embeds' ), 10, 3 );
	}

	/**
	 * Wrap embeds and add an intrinsic ratio for videos.
	 *
	 * - Set intrinsic ratio to make video responsive and minimize content
	 *   jumps before lazy loading.
	 *
	 * @since 0.2.0
	 *
	 * @param string $return The returned oEmbed HTML.
	 * @param object $data   A data object result from an oEmbed provider.
	 * @param string $url    The URL of the content to be embedded.
	 */
	public function wrap_embeds( $return, $data, $url ) {
		if ( ! is_object( $data ) || empty( $data->type ) ) {
			return $return;
		}

		$classes[]   = 'embed';
		$ratio_style = '';

		if ( 'video' === $data->type ) {
			$classes[] = 'embed--video';

			// Set intrinsic ratio.
			if ( isset( $data->width ) && isset( $data->height ) ) {
				$ratio       = ( $data->height / $data->width ) * 100;
				$ratio_style = ' style="padding-bottom:' . esc_attr( $ratio ) . '%;"';
			}
		}

		return '<div class="' . esc_attr( implode( ' ', $classes ) ) . '"' . $ratio_style . '>' . $return . '</div>';
	}
}
