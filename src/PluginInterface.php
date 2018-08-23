<?php
/**
 * Plugin interface.
 *
 * @package    SpeedRunner
 * @subpackage Plugin
 * @since      0.1.0
 */

namespace Sup\SpeedRunner;

/**
 * Plugin interface.
 *
 * @since   0.1.0
 */
interface PluginInterface {
	/**
	 * Initialise the plugin.
	 *
	 * @return $this Returns itself for easier method chaining
	 */
	public function run();

	/**
	 * Get the plugin directory path.
	 *
	 * @return string Path to the plugin directory
	 */
	public function get_directory();

	/**
	 * Set the plugin's directory path.
	 *
	 * @param string $directory Absolute path to the plugin directory.
	 * @return $this Returns itself for easier method chaining
	 */
	public function set_directory( $directory );

	/**
	 * Retrieve the path to a file in the plugin.
	 *
	 * @param  string $path Optional. Path relative to the plugin root.
	 * @return string
	 */
	public function get_path( $path = '' );

	/**
	 * Retrieve the absolute path for the main plugin file.
	 *
	 * @return string
	 */
	public function get_file();

	/**
	 * Set the path to the main plugin file.
	 *
	 * @param  string $file Absolute path to the main plugin file.
	 * @return $this Returns itself for easier method chaining
	 */
	public function set_file( $file );

	/**
	 * Retrieve the URL for a file in the plugin.
	 *
	 * @param  string $path Optional. Path relative to the plugin root.
	 * @return string
	 */
	public function get_url( $path = '' );

	/**
	 * Set the plugin's url.
	 *
	 * @param string $url URL to the plugin root directory.
	 * @return $this Returns itself for easier method chaining
	 */
	public function set_url( $url );

	/**
	 * Register a hook provider.
	 *
	 * @param  HookProviderInterface $provider Hook provider.
	 * @return $this
	 */
	public function register_hooks( HookProviderInterface $provider );
}
