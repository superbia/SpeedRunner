<?php
/**
 * Hook provider interface.
 *
 * @package Blackbird
 * @since   0.1.0
 */

namespace Sup\Blackbird;

/**
 * Hook provider interface.
 *
 * @package Blackbird
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
