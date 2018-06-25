<?php
/**
 * Plugin aware trait.
 *
 * @package Blackbird
 * @since   0.1.0
 */

namespace Sup\Blackbird;

/**
 * Plugin aware trait.
 *
 * @package Blackbird
 * @since   0.1.0
 */
trait PluginAwareTrait {
	/**
	 * Main plugin instance.
	 *
	 * @var PluginInterface
	 */
	protected $plugin;

	/**
	 * Set the main plugin instance.
	 *
	 * @param PluginInterface $plugin Main plugin instance.
	 * @return $this
	 */
	public function set_plugin( PluginInterface $plugin ) {
		$this->plugin = $plugin;
		return $this;
	}
}
