<?php
/**
 * Plugin aware interface.
 *
 * @package SpeedRunner
 * @since   0.1.0
 */

namespace Sup\SpeedRunner;

/**
 * Plugin aware interface.
 *
 * @package SpeedRunner
 * @since 0.1.0
 */
interface PluginAwareInterface {
	/**
	 * Set the main plugin instance.
	 *
	 * @param  PluginInterface $plugin Main plugin instance.
	 * @return $this
	 */
	public function set_plugin( PluginInterface $plugin );
}
