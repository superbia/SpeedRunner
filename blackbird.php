<?php
/**
 * Plugin Name:  Blackbird
 * Plugin URI:   https://bitbucket.org/superbiaweb/blackbird
 * Description:  Mach 3+ speed optimisations for WordPress.
 * Author:       Dylan Nichols, Superbia
 * Author URI:   https://superbia.com.au
 * Text Domain:  blackbird
 * Domain Path:  /languages
 * Version:      0.2.1
 * License:      GPL v3
 *
 * @package      Blackbird
 * @subpackage   Plugin
 */

namespace Sup\Blackbird;

use Sup\Blackbird\Plugin;
use Sup\Blackbird\Feature;

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
 * @return Blackbird
 */
function blackbird() {
	static $instance;

	if ( null === $instance ) {
		$instance = new Plugin();
	}

	return $instance;
}

$blackbird = blackbird()
	->set_directory( plugin_dir_path( __FILE__ ) )
	->set_url( plugins_url( '', __FILE__ ) )
	->run();

/**
 * Load template functions.
 */
require $blackbird->get_path( 'includes/template-tags/images.php' );

/**
 * Load theme features.
 */
$blackbird
	->features()
	->register( new Feature\CdnJquery( $blackbird ) )
	->register( new Feature\LazyLoad( $blackbird ) );
