<?php

/**
 * PLUGIN_NAME Form Loader and Wrapper
 *
 * Wraps ValidFormBuilder in a slightly more friendly
 * manner.
 *
 * @package   PLUGIN_PACKAGE
 * @author    PLUGIN_AUTHOR_NAME <PLUGIN_AUTHOR_EMAIL>
 * @copyright COPYRIGHT
 * @license   PLUGIN_LICENSE_NAME
 * @link      PLUGIN_URI
 */

namespace PLUGIN_PACKAGE;

use ValidFormBuilder\ValidForm;

class Form {
	public static function enqueue_and_require() {
		wp_enqueue_script( "PLUGIN_FUNC_PREFIX-validform-js", PLUGIN_CONST_PREFIX_PLUGIN_URL_ROOT . "lib/validformbuilder/js/validform.js", [ "jquery" ], "1" );

		// The validformbuilder styles are a bit dated, so we roll our own
		//wp_enqueue_style( "PLUGIN_FUNC_PREFIX-validform-css", PLUGIN_CONST_PREFIX_PLUGIN_URL_ROOT . "lib/validformbuilder/css/validform.css", [], "1" );

		$path = PLUGIN_CONST_PREFIX_PLUGIN_ROOT . "lib/validformbuilder/classes/ValidFormBuilder";
		require_once( $path . "/ClassDynamic.php" );
		require_once( $path . "/Base.php" );
		require_once( $path . "/Area.php" );
		require_once( $path . "/Element.php" );
		$files = scandir( $path );

		foreach ( $files as $file ) {
			if ( ! in_array( $file, [ '.', '..' ] ) ) {
				require_once( $path . "/" . $file );
			}
		}
	}

	/**
	 * @param $name
	 * @param $title
	 * @param $action
	 * @param $nonce_id
	 *
	 * @return ValidForm
	 */
	public static function create( $name, $title, $action, $fields, $values, $nonce_id ) {
		$form = new ValidForm( $name, $title, $action );

		$values = self::add_nonce( $form, $nonce_id, $values );

		return self::add_fields( $form, $fields, $values );
	}

	public static function is_submitted_and_valid( $form, $nonce_id ) {
		if ( ! isset( $_POST['wp-nonce'] ) ) {
			return false;
		}
		if ( ! $form->isSubmitted() ) {
			return false;
		}

		if ( ! wp_verify_nonce( $_POST['wp-nonce'], $nonce_id ) ) {
			return false;
		}

		return $form->isValid();
	}

	public static function get_submitted_values( $form, $fields, $defaults, $old_values ) {
		$values = [];
		foreach ( $defaults as $fname => $fval ) {
			$value            = $form->getValidField( $fname )->getValue();
			$values[ $fname ] = $value;
		}

		// Clear up any issues with the area block
		// The above call will set child fields to null
		// if the area checkbox is unchecked
		foreach ( $fields as $fname => $field ) {
			if ( $field['type'] == "area" && $values[ $fname ] == false ) {
				foreach ( $field['fields'] as $afkey => $afval ) {
					if ( $values[ $afkey ] == null ) {
						// Don't store null; replace with the old values
						$values[ $afkey ] = $old_values[ $afkey ];
					}
				}
			}
		}

		return $values;
	}

	/**
	 * @param $form ValidForm
	 * @param $nonce_id string
	 *
	 * @return ValidForm
	 */
	public static function add_nonce( $form, $nonce_id, $field_values ) {
		// Use wp csrf via nonce
		$form->setUseCsrfProtection( false );
		$form->addHiddenField( 'wp-nonce', ValidForm::VFORM_STRING );
		$field_values['wp-nonce'] = wp_create_nonce( $nonce_id );

		return $field_values;
	}

	/**
	 * @param $form ValidForm
	 * @param $fields array[] Multidimensional definition for form fields.
	 *
	 * @return ValidForm
	 */
	public static function add_fields( $form, $fields, $values ) {
		foreach ( $fields as $fname => $field ) {
			switch ( $field['type'] ) {
				case 'area':
					self::_area( $form, $fname, $field, $field['toggle'], $values[ $fname ] );
					// Areas don't have default values
					unset( $values[ $fname ] );
					break;
				case 'fieldset':
					self::_area( $form, $fname, $field, false, false );
					break;
				default:
					self::_field( $form, $fname, $field );
					break;
			}
		}
		$form->setDefaults( $values );

		return $form;
	}

	private static function _area( $form, $name, $def, $is_togglable, $value ) {
		$area = $form->addArea( $def['label'], $is_togglable, $name, $value );
		foreach ( $def['fields'] as $fname => $field ) {
			self::_field( $area, $fname, $field );
		}
	}


	/**
	 * @param $form_obj ValidForm
	 * @param $name string
	 * @param $def array
	 *
	 * @return void
	 */
	private static function _field( $form_obj, $name, $def ) {
		$validation_rules  = $def['validation']['rules'] ?? [];
		$validation_errors = $def['validation']['errors'] ?? [];

		switch ( $def['type'] ) {
			case ValidForm::VFORM_CHECK_LIST:
			case ValidForm::VFORM_RADIO_LIST:
			case ValidForm::VFORM_SELECT_LIST:
				$group = $form_obj->addField( $name, $def['label'], $def['type'], $validation_rules, $validation_errors );
				foreach ( $def['options'] as $opname => $opval ) {
					$group->addField( $opval, $opname );
				}
				break;
			default:
				$form_obj->addField( $name, $def['label'], $def['type'], $validation_rules, $validation_errors );
				break;

		}
	}
}