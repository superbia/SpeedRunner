<?php
/**
 * Feature base.
 *
 * @package    Blackbird
 * @subpackage Plugin
 * @since      0.1.0
 */

namespace Sup\Blackbird\Feature;

use Sup\Blackbird\HookProviderInterface;
use Sup\Blackbird\PluginInterface;

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
	 * Feature slug.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	protected $slug;

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
		return 'blackbird-' . $this->id;
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
