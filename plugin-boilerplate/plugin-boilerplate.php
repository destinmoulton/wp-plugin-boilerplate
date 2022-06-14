<?php

/**
 * @package   PLUGIN_PACKAGE
 * @author    PLUGIN_AUTHOR <PLUGIN_AUTHOR_EMAIL>
 * @copyright COPYRIGHT_YEAR COPYRIGHT_HOLDER
 * @license   PLUGIN_LICENSE
 * @link      PLUGIN_URI
 *
 * Plugin Name:     PLUGIN_NAME
 * Plugin URI:      PLUGIN_URI
 * Description:     PLUGIN_DESCRIPTION
 * Version:         PLUGIN_VERSION
 * Author:          PLUGIN_AUTHOR
 * Author URI:      PLUGIN_AUTHOR_URI
 * Text Domain:     PLUGIN_TEXT_DOMAIN
 * License:         PLUGIN_LICENSE
 * License URI:     PLUGIN_LICENSE_URI
 * Requires PHP:    7.0
 */

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

define( 'PLUGIN_PREFIX_VERSION', '1.0.0' );
define( 'PLUGIN_PREFIX_TEXTDOMAIN', 'PLUGIN_TEXT_DOMAIN' );
define( 'PLUGIN_PREFIX_NAME', 'PLUGIN_NAME' );
define( 'PLUGIN_PREFIX_PLUGIN_ROOT', plugin_dir_path( __FILE__ ) );
define( 'PLUGIN_PREFIX_PLUGIN_ABSOLUTE', __FILE__ );
define( 'PLUGIN_PREFIX_MIN_PHP_VERSION', '7.0' );
define( 'PLUGIN_PREFIX_WP_VERSION', '5.3' );