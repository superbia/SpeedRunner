<?php
/**
 * Theme features.
 *
 * @package    Blackbird
 * @subpackage Plugin
 * @since 0.1.0
 */

namespace Sup\Blackbird\Provider;

use Sup\Blackbird\HookProviderInterface;
use Sup\Blackbird\PluginAwareInterface;
use Sup\Blackbird\PluginAwareTrait;

/**
 * Administration assets provider class.
 */
class ThemeFeatures implements HookProviderInterface, PluginAwareInterface {

	use PluginAwareTrait;

	/**
	 * Register hooks.
	 */
	public function register_hooks() {
		add_action( 'after_setup_theme', array( $this, 'maybe_require_feature_mods' ), 100 );
	}

	/**
	 * Conditionally require theme features.
	 *
	 * Checks for theme support before requiring the feature file
	 *
	 * @since 0.1.0
	 */
	public function maybe_require_feature_mods() {
		foreach ( $this->get_features() as $feature ) {
			if ( current_theme_supports( 'blackbird-' . $feature ) ) {
				require_once $this->plugin->get_path( '/features/' . $feature . '.php' );
			}
		}
	}

	/**
	 * All feature modifications.
	 *
	 * @since 0.1.0
	 *
	 * @return array An array of features modifications
	 */
	public static function get_features() {
		return array(
			'enable-cdn-jquery',
		);
	}

}
