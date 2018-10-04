<?php
/**
 * Plugin Name:  SpeedRunner
 * Plugin URI:   https://bitbucket.org/superbiaweb/speedrunner
 * Description:  Mach 3+ speed optimisations for WordPress.
 * Author:       Dylan Nichols, Superbia
 * Author URI:   https://superbia.com.au
 * Text Domain:  speedrunner
 * Domain Path:  /languages
 * Version:      0.4.0
 * License:      GPL v3
 *
 * @package      SpeedRunner
 * @subpackage   Plugin
 */

namespace Sup\SpeedRunner;

use Sup\SpeedRunner\Plugin;
use Sup\SpeedRunner\Feature;

/**
 * The plugin version.
 */
define( 'SPDRNR_PLUGIN_VERSION', '0.4.0' );

/**
 * Require the Composer autoloader
 * - assumes vendor is packaged with the plugin, or
 * - the autoloader is already present as part of a larger composer managed stack
 */
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

/**
 * Retrieve the main plugin instance.
 *
 * @since 0.1.0
 *
 * @return SpeedRunner
 */
function speedrunner() {
	static $instance;

	if ( null === $instance ) {
		$instance = new Plugin();
	}

	return $instance;
}

$speedrunner = speedrunner()
	->set_directory( plugin_dir_path( __FILE__ ) )
	->set_url( plugins_url( '', __FILE__ ) )
	->run();

/**
 * Load template functions.
 */
require $speedrunner->get_path( 'includes/template-tags/images.php' );

/**
 * Load theme features.
 */
$speedrunner
	->features()
	->register( new Feature\AssetRevisioning( $speedrunner ) )
	->register( new Feature\CdnJquery( $speedrunner ) )
	->register( new Feature\LazyLoad( $speedrunner ) );
