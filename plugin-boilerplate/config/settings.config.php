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
 * Get the settings.
 *
 * The default configuration uses the `formr` library
 * to build the settings form and perform validation.
 *
 * For a list of all formr validation and sanitization rules, see:
 * https://formr.github.io/validation/
 *
 * For a list of all input types:
 * https://formr.github.io/methods/#text-inputs
 *
 * Multiple `checkbox` and `radio` elements are
 * configured in the SettingsTool class.
 *
 * @return \string[][]
 */
function PLUGIN_FUNC_PREFIX_config_default_settings() {
	return [
		[
			'type'        => 'text',
			'label'       => 'Setting 1: A Simple Text Field',
			'name'        => 'setting1',
			'description' => 'This text appears along with the field.',
			'validation'  => 'required',
			'default'     => 'Hi!'
		],
		[
			'type'       => 'select', //or select_multiple
			'label'      => 'Choose a size:',
			'name'       => 'size',
			// Change the `default` for a select to
			// an array and it automagically become a <select muliple>
			'default'    => 'small',
			'validation' => 'required',
			// Formr includes a set of built-in options
			// ie. states, months, countries, etc...
			// To use them just change it to 'options' => 'states'
			'options'    => [
				'small' => 'Small',
				'large' => 'Large'
			]
		]
	];
}
