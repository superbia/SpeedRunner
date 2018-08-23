<?php
/**
 * CDN jQuery feature.
 *
 * @package SpeedRunner
 * @since 0.1.0
 */

namespace Sup\SpeedRunner\Feature;

/**
 * CDN jQuery class.
 */
class CdnJquery extends AbstractFeature {
	/**
	 * Feature id.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	protected $id = 'enable-cdn-jquery';

	/**
	 * Register feature hooks.
	 *
	 * @since 0.1.0
	 */
	public function register_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_cdn_jquery' ), 100 );
		add_filter( 'wp_resource_hints', array( $this, 'jquery_resource_hints' ), 10, 2 );
	}

	/**
	 * Enable google CDN version of jQuery.
	 */
	public function register_cdn_jquery() {
		$handle       = 'jquery';
		$deps         = [];
		$version      = wp_scripts()->registered[ $handle ]->ver;
		$src          = '//ajax.googleapis.com/ajax/libs/jquery/' . $version . '/jquery.min.js';
		$fallback_src = wp_scripts()->base_url . wp_scripts()->registered['jquery-core']->src;
		$in_footer    = true;

		wp_deregister_script( $handle );
		wp_register_script(
			$handle,
			esc_url( $src ),
			$deps,
			$version,
			$in_footer
		);
		wp_add_inline_script(
			$handle,
			'window.jQuery || document.write(\'<script src="' . esc_url( $fallback_src ) . '"><\/script>\')' // PHPCS:IGNORE WordPress.WP.EnqueuedResources.NonEnqueuedScript
		);
	}

	/**
	 * Resource hint for dns prefetching of google CDN jQuery.
	 *
	 * @param array  $urls          URLs to print for resource hints.
	 * @param string $relation_type The relation type the URLs are printed for, e.g. 'preconnect' or 'prerender'.
	 */
	public function jquery_resource_hints( $urls, $relation_type ) {
		if ( 'dns-prefetch' === $relation_type ) {
			$urls[] = 'ajax.googleapis.com';
		}

		return $urls;
	}
}
