<?php
/**
 * PLUGIN_NAME Settings Definition
 *
 *
 * @package   PLUGIN_PACKAGE
 * @author    PLUGIN_AUTHOR_NAME <PLUGIN_AUTHOR_EMAIL>
 * @copyright COPYRIGHT
 * @license   PLUGIN_LICENSE_NAME
 * @link      PLUGIN_URI
 */

namespace PLUGIN_PACKAGE;

use ValidFormBuilder\ValidForm;

/**
 * Define the Settings
 *
 * The default configuration uses the `ValidFormBuilder` library
 * to build the settings form and perform validation.
 *
 * This file defines the setting default values in a
 * string keyed array:
 *     PLUGIN_CONST_PREFIX_SETTING_DEFAULTS = [
 *          'setting_key'=>'Value',
 *          'setting_key_for_numeric'=>42
 *     ];
 *
 * These settings are used as the generic defaults for the plugin.
 *
 * The `PLUGIN_PACKAGE\Settings` class ('class-settings.php') will just return these values.
 *
 * The `settings-tool` ('admin/tools/settings-tool') allows you to define an interface
 * for editing the settings in a form, validating them, and saving the settings to the WP options table.
 *
 * @const array[][]
 */
const PLUGIN_CONST_PREFIX_SETTING_DEFAULT_VALUES = [
	'bootstrap_enabled'   => true,
	'bootstrap_js_url'    => "https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js",
	'bootstrap_css_url'   => "https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css",
	'test_select_box'     => [ 'bar' ], // 'bar' will be the selected option (see OPTIONS below)
	'test_checkbox_group' => [ 'blue' ]
];


/**
 * Permissions
 *
 * What tools are allowed and disallowed via permissions
 *
 * @const array
 */
const PLUGIN_CONST_PREFIX_FEATURE_PERMISSIONS = [
	// General Features
	'logger'        => PLUGIN_CONST_PREFIX_MIN_ADMIN_CAPABILITY,

	// Tools
	// The default-tool permission will be used for undefined tools
	'default-tool'  => PLUGIN_CONST_PREFIX_MIN_ADMIN_CAPABILITY,
	'example-tool'  => PLUGIN_CONST_PREFIX_MIN_ADMIN_CAPABILITY,
	'settings-tool' => PLUGIN_CONST_PREFIX_MIN_ADMIN_CAPABILITY,
	'log-tool'      => PLUGIN_CONST_PREFIX_MIN_ADMIN_CAPABILITY,
];

/**
 * Log Console Colors
 *
 * These colors will style the `console.groupCollapsed()`.
 *
 * Just a nice way to differentiate the console messages.
 *
 * Used by `includes/class-logger.php`
 *
 * @const array
 */
const PLUGIN_CONST_PREFIX_LOGGER_CONSOLE_COLORS = [
	'default' => 'dimgray',
	'error'   => 'maroon',
	'success' => 'forestgreen',
	'info'    => 'dodgerblue',
	'warn'    => 'darkorange'
];

/**
 * Logger Default Settings
 *
 * Make sure to click "Reset Your Log Tool Metadata" in the
 * Log Tool after changing these defaults.
 *
 * Boolean values are stored as int so use 1 or 0.
 *
 * @const array
 */
const PLUGIN_CONST_PREFIX_LOGGER_DEFAULT_SETTINGS = [
	// Enabled by default?
	'enabled'   => 0,

	// Log to either `console` AND/OR `file`
	'log_to'    => [
		'console' // js console
	],

	// PHP Backtrace
	'backtrace' => 0,


	// Logger PHP Error/Exception Capturing
	'php'       => [
		// Log PHP Errors?
		'log_php_errors'        => 1,
		// Allows you to set the error_reporting(int) level
		'error_reporting_level' => E_ALL
	],

	// JS Console Defaults
	'console'   => [
		// Date format in standard PHP notation per `date()`
		'date_format' => "c"
	],

	// Log File Defaults
	'file'      => [
		'name'        => "PLUGIN_SLUG.log",
		// Absolute path where the log should be stored
		'dir'         => WP_CONTENT_DIR,
		// URI path to the log file
		'uri_path'    => "/wp-content/",
		// Each log entry is separated with a string
		'separator'   => PHP_EOL . "\040\040\011\011\040\040" . PHP_EOL,
		// Date format in standard PHP notation per `date()`
		'date_format' => "c"
	]
];
