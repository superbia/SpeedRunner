<?php
/**
 * Feature base.
 *
 * @package    SpeedRunner
 * @subpackage Plugin
 * @since      0.1.0
 */

namespace Sup\SpeedRunner\Feature;

use Sup\SpeedRunner\HookProviderInterface;
use Sup\SpeedRunner\PluginInterface;

/**
 * Base feature class.
 */
abstract class AbstractFeature implements HookProviderInterface {
	/**
	 * Feature id.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	protected $id;

	/**
	 * Plugin instance.
	 *
	 * @since 0.1.0
	 * @var PluginInterface
	 */
	protected $plugin;

	/**
	 * Constructor method.
	 *
	 * @since 0.1.0
	 *
	 * @param PluginInterface $plugin Plugin instance.
	 */
	public function __construct( PluginInterface $plugin = null ) {
		$this->plugin = $plugin;
	}

	/**
	 * Get the id of the feature.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_id() {
		return 'speedrunner-' . $this->id;
	}

	/**
	 * Method for loading the feature.
	 *
	 * Typically occurs after the text domain has been loaded.
	 *
	 * @since 0.1.0
	 *
	 * @return $this
	 */
	public function load() {
		return $this;
	}

	/**
	 * Register feature hooks.
	 *
	 * @since 0.1.0
	 */
	abstract public function register_hooks();

	/**
	 * Whether the feature is supported.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_supported() {
		return current_theme_supports( $this->get_id() );
	}
}
