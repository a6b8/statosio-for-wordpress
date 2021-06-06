<?php
/**
 * Plugin Name:       Statosio.js Charts as Shortcut
 * Plugin URI:        https://www.github.com/a6b8/statosio-wordpress
 * Description:       Statosio generate charts in a .svg format. Works with prawn-svg to generate .pdf documents.
 * Version:           0.0.1
 * Author:            Andreas Banholzer
 * Author URI:        https://www.13plus4.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ABS
 *
 * @link              https://www.github.com/a6b8/statosio-wordpress
 * @package           ABS
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define global constants.
 *
 * @since 1.0.0
 */
// Plugin version.
if ( ! defined( 'ABS_VERSION' ) ) {
	define( 'ABS_VERSION', '2.0.0' );
}

if ( ! defined( 'ABS_NAME' ) ) {
	define( 'ABS_NAME', trim( dirname( plugin_basename( __FILE__ ) ), '/' ) );
}

if ( ! defined( 'ABS_DIR' ) ) {
	define( 'ABS_DIR', WP_PLUGIN_DIR . '/' . ABS_NAME );
}

if ( ! defined( 'ABS_URL' ) ) {
	define( 'ABS_URL', WP_PLUGIN_URL . '/' . ABS_NAME );
}


/**
 * Link.
 *
 * @since 1.0.0
 */
require_once( ABS_DIR . '/shortcode/shortcode-demo.php' );


/**
 * Link.
 *
 * @since 1.0.0
 */
require_once( ABS_DIR . '/shortcode/shortcode-chart.php' );