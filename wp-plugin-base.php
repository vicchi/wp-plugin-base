<?php

/*
 * Name: wp-plugin-base
 * Description: Base class for developing WordPress plugins; contains helper functions to add WordPress hooks consistently and sanitise hook method names.
 * Version: 2.0.0
 * Author: Gary Gale, Travis Smith
 * Author URI: http://www.garygale.com
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Acknowledgements: Based on WPS_Plugin_Base_v1 by Travis Smith (http://wpsmith.net)
 */

if (!class_exists ('WP_PluginBase')) {
	abstract class WP_PluginBase {
		protected static $instance;
		protected function hook($hook) {
			$priority = 10;
			$method = $this->sanitise_method($hook);
			$args = func_get_args();
			unset ($args[0]);
			foreach ((array)$args as $arg) {
				if (is_int($arg)) {
					$priority = $arg;
				}
				else {
					$method = $arg;
				}
			}	// end-foreach
			return add_action($hook, array ($this, $method), $priority, 999);
		}

		private function sanitise_method($method) {
			return str_replace (['.', '-'], ['_DOT_', '_DASH'], $method);
		}

		abstract public static function get_instance();
	}	// end-class WP_PluginBase
}

?>
