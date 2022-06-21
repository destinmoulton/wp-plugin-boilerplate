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
const PLUGIN_CONST_PREFIX_SETTINGS = [
	'test-setting' => "This is a test setting."
];
