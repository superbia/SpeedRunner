<?php
/**
 * Main plugin class
 *
 * @package    SpeedRunner
 * @subpackage Plugin
 * @since      0.1.0
 */

namespace Sup\SpeedRunner;

/**
 * The core plugin class.
 */
class Plugin extends AbstractPlugin {
	/**
	 * Features.
	 *
	 * @since 0.1.0
	 * @var FeatureCollection
	 */
	protected $features;

	/**
	 * Constructor method.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->features = new FeatureCollection();
	}

	/**
	 * Retreive the plugin features.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function features() {
		return $this->features;
	}

	/**
	 * Load the plugin.
	 *
	 * @since 0.1.0
	 */
	public function plugins_loaded() {
		add_action( 'after_setup_theme', array( $this, 'load_features' ), 100 );
	}

	/**
	 * Loads the plugin features.
	 *
	 * @since 0.1.0
	 */
	public function load_features() {
		foreach ( $this->features as $feature ) {
			// Only load features supported by the current theme.
			if ( ! $feature->is_supported() ) {
				continue;
			}

			$this->register_hooks( $feature->load() );
		}
	}
}
