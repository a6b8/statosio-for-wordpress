<?php
/**
 * Plugin Name:       Statosio Shortcodes
 * Plugin URI:        https://www.github.com/a6b8/statosio.wp
 * Description:       Statosio for Wordpress is based on statosio.js and helps to generate simple charts with Wordpress shortcodes
 * Version:           0.3.6
 * Author:            Andreas Banholzer
 * Author URI:        https://www.13plus4.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       P134
 *
 * @link              https://www.github.com/a6b8/statosio-wordpress
 * @package           P134
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
if ( ! defined( 'P134_VERSION' ) ) {
	define( 'P134_VERSION', '2.0.0' );
}

if ( ! defined( 'P134_NAME' ) ) {
	define( 'P134_NAME', trim( dirname( plugin_basename( __FILE__ ) ), '/' ) );
}

if ( ! defined( 'P134_DIR' ) ) {
	define( 'P134_DIR', WP_PLUGIN_DIR . '/' . P134_NAME );
}

if ( ! defined( 'P134_URL' ) ) {
	define( 'P134_URL', WP_PLUGIN_URL . '/' . P134_NAME );
}


/**
 * Link.
 *
 * @since 1.0.0
 */
require_once( P134_DIR . '/shortcode/shortcode-demo.php' );


/**
 * Link.
 *
 * @since 1.0.0
 */
require_once( P134_DIR . '/shortcode/shortcode-chart.php' );


/**
 * Link.
 *
 * @since 1.0.0
 */
require_once( P134_DIR . '/option_page/index.php' );