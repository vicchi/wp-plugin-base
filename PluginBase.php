<?php
/*
 * Name: PluginBase
 * Description: Abstract base class for developing WordPress plugins; contains helper functions to add WordPress hooks consistently and sanitise hook method names.
 * Version: 2.0.1
 * Author: Gary Gale, Travis Smith
 * Author URI: http://www.garygale.com
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Acknowledgements: Based on WPS_Plugin_Base_v1 by Travis Smith (http://wpsmith.net)
 */

if (!class_exists('PluginBase')) {
    abstract class PluginBase {
        protected static $plugin_name;

        protected function __construct() {
            isset(self::$plugin_name) or die(__CLASS__ . ': Bad plugin implementation; self::$plugin_name is not set');
        }

        abstract protected static function get_instance();

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

        protected static function get_option() {
            $num_args = func_num_args();
            $options = get_option(self::$plugin_name);

            if ($num_args > 0) {
                $args = func_get_args();
                $key = $args[0];
                $value = '';
                if (isset($options[$key])) {
                    $value = $options[$key];
                }
                return $value;
            }

            return $options;
        }

        protected static function set_option($key, $value) {
            $options = get_option(self::$plugin_name);
            $options[$key] = $value;
            update_option(self::$plugin_name, $options);
        }

        protected static function update_option($settings) {
            update_option(self::$plugin_name, $settings);
        }

        private function sanitise_method($method) {
            return str_replace (['.', '-'], ['_DOT_', '_DASH'], $method);
        }
    }   // end-class PluginBase
}   // end-if (!class_exists(PluginBase))

?>
