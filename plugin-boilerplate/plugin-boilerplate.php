<?php

/**
 * @package   PLUGIN_PACKAGE
 * @author    PLUGIN_AUTHOR_NAME <PLUGIN_AUTHOR_EMAIL>
 * @copyright COPYRIGHT
 * @license   PLUGIN_LICENSE_NAME
 * @link      PLUGIN_URI
 *
 * Plugin Name:     PLUGIN_NAME
 * Plugin URI:      PLUGIN_URI
 * Description:     PLUGIN_DESCRIPTION
 * Version:         PLUGIN_VERSION
 * Author:          PLUGIN_AUTHOR_NAME
 * Author URI:      PLUGIN_AUTHOR_URI
 * Text Domain:     PLUGIN_TEXT_DOMAIN
 * License:         PLUGIN_LICENSE_NAME
 * License URI:     PLUGIN_LICENSE_URI
 * Requires PHP:    PLUGIN_PHP_VERSION
 */

// If this file is called directly, abort.
defined('ABSPATH') or die('Error. You cannot directly access this file.');

define( 'PLUGIN_CONST_PREFIX_VERSION', '1.0.0' );
define( 'PLUGIN_CONST_PREFIX_TEXTDOMAIN', 'PLUGIN_TEXT_DOMAIN' );
define( 'PLUGIN_CONST_PREFIX_NAME', 'PLUGIN_NAME' );
define( 'PLUGIN_CONST_PREFIX_PLUGIN_ROOT', plugin_dir_path( __FILE__ ) );
define( 'PLUGIN_CONST_PREFIX_PLUGIN_ABSOLUTE', __FILE__ );
define( 'PLUGIN_CONST_PREFIX_MIN_PHP_VERSION', '7.0' );
define( 'PLUGIN_CONST_PREFIX_WP_VERSION', '5.3' );
define( 'PLUGIN_CONST_PREFIX_MIN_ADMIN_CAPABILITY', 'PLUGIN_MIN_ADMIN_CAPABILITY');


add_action(
	'plugins_loaded',
	static function () {
		require_once(PLUGIN_CONST_PREFIX_PLUGIN_ROOT."/includes/class-plugin.php");
		\PLUGIN_PACKAGE\Plugin::run();
	}
);