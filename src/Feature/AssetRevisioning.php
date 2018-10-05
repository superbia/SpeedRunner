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
	protected $manifest = [];

	/**
	 * Method for loading the feature.
	 *
	 * @return $this
	 */
	public function load() {
		$defaults = [
			'dist_path' => 'assets/dist/',
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
		add_filter( 'theme_file_uri', array( $this, 'hashed_theme_file_url' ), 10, 2 );
	}

	/**
	 * Load asset manifest.
	 */
	public function load_manifest() {
		$manifest_path  = get_theme_file_path( $this->get_dist_path() . 'rev-manifest.json' );
		$this->manifest = file_exists( $manifest_path )
			? json_decode( file_get_contents( $manifest_path ), true ) // phpcs:ignore
			: [];
	}

	/**
	 * Filters theme file urls to return hashed version.
	 *
	 * @param string $url  The file URL.
	 * @param string $file The requested file to search for.
	 */
	public function hashed_theme_file_url( $url, $file ) {
		if ( WP_DEV_SERVER || empty( $this->manifest ) ) {
			return $url;
		}

		$file_path = str_replace( $this->get_dist_path(), '', $file );

		if ( array_key_exists( $file_path, $this->manifest ) ) {
			$base_url = str_replace( $file, '', $url );
			return $base_url . $this->get_dist_path() . $this->manifest[ $file_path ];
		}

		return $url;
	}

	/**
	 * Get the assets dist path.
	 *
	 * @since 0.5.0
	 *
	 * @return string
	 */
	public function get_dist_path() {
		return trailingslashit( ltrim( $this->args->dist_path, '/' ) );
	}
}
