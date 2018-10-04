<?php
/**
 * Asset revisioning feature.
 *
 * Static asset revisioning by appending content hash to filenames unicorn.css → unicorn-d41d8cd98f.css.
 *
 * @link https://github.com/sindresorhus/gulp-rev
 * @package SpeedRunner
 * @since 0.5.0
 */

namespace Sup\SpeedRunner\Feature;

use Sup\SpeedRunner\Provider;

/**
 * CDN jQuery class.
 */
class AssetRevisioning extends AbstractFeature {
	/**
	 * Feature id.
	 *
	 * @var string
	 */
	protected $id = 'enable-asset-revisioning';

	/**
	 * Feature args.
	 *
	 * @var array
	 */
	protected $args;

	/**
	 * Manifest.
	 *
	 * @var array
	 */
	protected $manifest;

	/**
	 * Method for loading the feature.
	 *
	 * @return $this
	 */
	public function load() {
		$defaults = [
			'dist_path' => '/assets/dist/',
		];

		$args       = get_theme_support( $this->get_id() );
		$args       = ( is_array( $args ) ) ? $args[0] : [];
		$args       = wp_parse_args( $args, $defaults );
		$this->args = (object) $args;

		return $this;
	}

	/**
	 * Register feature hooks.
	 */
	public function register_hooks() {
		add_filter( 'init', array( $this, 'load_manifest' ) );
		add_filter( 'style_loader_src', array( $this, 'filter_enqueued_urls' ), 10, 2 );
		add_filter( 'script_loader_src', array( $this, 'filter_enqueued_urls' ), 10, 2 );
	}

	/**
	 * Load asset manifest.
	 */
	public function load_manifest() {
		$manifest_path  = trailingslashit( $this->get_dist_path() ) . 'rev-manifest.json';
		$this->manifest = file_exists( $manifest_path )
			? json_decode( file_get_contents( $manifest_path ), true ) // phpcs:ignore
			: [];
	}

	/**
	 * Load hashed asset filenames.
	 *
	 * @param string $src The source URL of the enqueued asset.
	 */
	public function filter_enqueued_urls( $src ) {
		if ( WP_DEV_SERVER ) {
			return $src;
		}

		$base_url  = $this->get_dist_url();
		$file_path = str_replace( $base_url, '', $src );
		$file_path = remove_query_arg( 'ver', $file_path );

		if ( array_key_exists( $file_path, $this->manifest ) ) {
			return $base_url . $this->manifest[ $file_path ];
		}

		return $src;
	}

	/**
	 * Get the assets dist path.
	 *
	 * @since 0.5.0
	 *
	 * @return string
	 */
	public function get_dist_path() {
		return get_template_directory() . trailingslashit( $this->args->dist_path );
	}

	/**
	 * Get the base assets url.
	 *
	 * @since 0.5.0
	 *
	 * @return string
	 */
	public function get_dist_url() {
		$base_path = ( 'style_loader_src' === current_filter() )
			? get_stylesheet_directory_uri()
			: get_template_directory_uri();
		return $base_path . trailingslashit( $this->args->dist_path );
	}
}
