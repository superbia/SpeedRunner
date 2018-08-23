<?php
/**
 * Hook provider interface.
 *
 * @package SpeedRunner
 * @since   0.1.0
 */

namespace Sup\SpeedRunner;

/**
 * Hook provider interface.
 *
 * @package SpeedRunner
 * @since 0.1.0
 */
interface HookProviderInterface {
	/**
	 * Register hooks for the plugin.
	 *
	 * @since 0.1.0
	 */
	public function register_hooks();
}
