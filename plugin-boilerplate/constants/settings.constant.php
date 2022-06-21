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
 * The default configuration uses the `formr` library
 * to build the settings form and perform validation.
 *
 * For a list of all formr validation and sanitization rules, see:
 * https://formr.github.io/validation/
 *
 * For a list of all input types:
 * https://formr.github.io/methods/#text-inputs
 *
 * NOTE: The 'string' Formr field has been replaced
 * with an 'attributes' array.
 *
 * @const array[][]
 */
const PLUGIN_CONST_PREFIX_SETTINGS = [
	[
		'type'       => 'text',
		'label'      => 'Setting 1: A Simple Text Field',
		'name'       => 'setting1',
		'id'         => 'setting1', // You can leave out the id if you want it to be the name
		'validation' => 'required',
		'default'    => 'Hi!',
		// An array of html attributes that will be added to the html
		'attributes' => [
			'class'       => 'text-css-class',
			'placeholder' => 'Placeholder text'
		]
	],
	[
		'type'       => 'select', //or select_multiple
		'label'      => 'Choose a size:',
		'name'       => 'size',
		// Change the `selected` for a select to
		// an array and it automagically become a <select muliple>
		'selected'   => [ 'small', 'large' ],
		'validation' => 'required',
		'attributes' => [
			'class' => 'class-for-select'
		],
		// Formr includes a set of built-in options
		// ie. states, months, countries, etc...
		// To use them just change it to 'options' => 'states'
		'options'    => [
			'small' => 'Small',
			'large' => 'Large'
		]
	],
	[
		'type'     => 'checkbox', //or select_multiple
		'label'    => 'Check or uncheck this setting:',
		'name'     => 'checky',
		'selected' => 'checked'
	]
];
