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
	'bootstrap_onoff'     => true,
	'bootstrap_js_url'    => "https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js",
	'bootstrap_css_url'   => "https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css",
	'test_select_box'     => [ 'bar' ], // 'bar' will be the selected option (see OPTIONS below)
	'test_checkbox_group' => [ 'blue' ]
];


/**
 * Permissions
 *
 * What tools are allowed and disallowed via permissions
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

