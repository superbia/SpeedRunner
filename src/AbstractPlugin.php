<?php
/**
 * Common plugin functionality.
 *
 * @package    SpeedRunner
 * @subpackage Plugin
 * @since      0.1.0
 */

namespace Sup\SpeedRunner;

/**
 * Abstract plugin class.
 *
 * @since   0.1.0
 */
abstract class AbstractPlugin implements PluginInterface {
	/**
	 * Absolute path to the main plugin directory.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	protected $directory;

	/**
	 * Absolute path to the main plugin file.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	protected $file;

	/**
	 * URL to the main plugin directory.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	protected $url;

	/**
	 * Initialise the plugin.
	 *
	 * @return $this Returns itself for easier method chaining
	 */
	public function run() {
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
		return $this;
	}

	/**
	 * Get the plugin directory path.
	 *
	 * @since 0.1.0
	 *
	 * @return string Path to the plugin directory
	 */
	public function get_directory() {
		return $this->directory;
	}

	/**
	 * Set the plugin's directory path.
	 *
	 * @since 0.1.0
	 *
	 * @param string $directory Absolute path to the plugin directory.
	 * @return $this Returns itself for easier method chaining
	 */
	public function set_directory( $directory ) {
		$this->directory = $directory;

		return $this;
	}

	/**
	 * Retrieve the path to a file in the plugin.
	 *
	 * @since 0.1.1
	 *
	 * @param  string $path Optional. Path relative to the plugin root.
	 * @return string
	 */
	public function get_path( $path = '' ) {
		return $this->directory . ltrim( $path, '/' );
	}

	/**
	 * Retrieve the absolute path for the main plugin file.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_file() {
		return $this->file;
	}

	/**
	 * Set the path to the main plugin file.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $file Absolute path to the main plugin file.
	 * @return $this Returns itself for easier method chaining
	 */
	public function set_file( $file ) {
		$this->file = $file;
		return $this;
	}

	/**
	 * Retrieve the URL for a file in the plugin.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $path Optional. Path relative to the plugin root.
	 * @return string
	 */
	public function get_url( $path = '' ) {
		return $this->url . ltrim( $path, '/' );
	}

	/**
	 * Set the plugin's url.
	 *
	 * @since 0.1.0
	 *
	 * @param string $url URL to the plugin root directory.
	 * @return $this Returns itself for easier method chaining
	 */
	public function set_url( $url ) {
		$this->url = rtrim( $url, '/' ) . '/';
		return $this;
	}

	/**
	 * Register a hook provider.
	 *
	 * @since 0.1.0
	 *
	 * @param  HookProviderInterface $provider Hook provider.
	 * @return $this
	 */
	public function register_hooks( HookProviderInterface $provider ) {
		if ( $provider instanceof PluginAwareInterface ) {
			$provider->set_plugin( $this );
		}

		$provider->register_hooks();
		return $this;
	}
}
