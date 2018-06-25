<?php
/**
 * Theme features.
 *
 * @package    Blackbird
 * @subpackage Plugin
 * @since 0.1.0
 */

namespace Sup\Blackbird\Provider;

use Sup\Blackbird\HookProviderInterface;
use Sup\Blackbird\PluginAwareInterface;
use Sup\Blackbird\PluginAwareTrait;

/**
 * Administration assets provider class.
 */
class ThemeFeatures implements HookProviderInterface, PluginAwareInterface {

	use PluginAwareTrait;

	/**
	 * Register hooks.
	 */
	public function register_hooks() {}
}
