<?php

/**
 * The PLUGIN_NAME Settings Tool
 *
 * @package   PLUGIN_PACKAGE
 * @author    PLUGIN_AUTHOR_NAME <PLUGIN_AUTHOR_EMAIL>
 * @copyright COPYRIGHT
 * @license   PLUGIN_LICENSE_NAME
 * @link      PLUGIN_URI
 */

namespace PLUGIN_PACKAGE\Admin\Tools;

use PLUGIN_PACKAGE\Settings;
use \ValidFormBuilder;
use \ValidFormBuilder\ValidForm;
use const PLUGIN_PACKAGE\PLUGIN_CONST_PREFIX_SETTINGS;

class SettingsTool extends AbstractAdminTool {
	protected $title;
	protected $slug = "settings-tool";
	protected $description;
	private $nonce_id;

	protected function init() {
		$this->title       = __( "Settings", PLUGIN_CONST_PREFIX_TEXTDOMAIN );
		$this->description = __( "Manage PLUGIN_NAME settings.", PLUGIN_CONST_PREFIX_TEXTDOMAIN );
		$this->nonce_id    = "PLUGIN_FUNC_PREFIX_save_settings_form";

		// Enqueue assets and require all the php files
		$this->load_validformbuilder();
	}

	/**
	 * @inheritDoc
	 */
	public function render() {

		$form = $this->setup_form( Settings::get_all() );

		$new_settings = [];
		if ( isset( $_POST[ $this->nonce_id ] ) ) {
			if ( wp_verify_nonce( $_POST[ $this->nonce_id ], $this->nonce_id ) ) {
//				if ( $form->isValid() && $form->isSubmitted() ) {
//
//					foreach ( PLUGIN_CONST_PREFIX_SETTINGS as $skey => $sval ) {
//						$new_settings = $form->getValidField( $skey )->getValue();
//
//					}
//					print_r( $new_settings );
//				}
			}
		}
		$pvars = [
			'form_html' => $form->toHtml()
		];
		$this->add_partial( plugin_dir_path( __FILE__ ) . "partials/settings-form.partial.php", $pvars );
	}

	/**
	 * @param $defaults The current settings
	 *
	 * @return ValidForm
	 */
	private function setup_form( $defaults ) {
		$options = \PLUGIN_PACKAGE\PLUGIN_CONST_PREFIX_SETTING_OPTIONS;
		$form    = new ValidForm( "cool_new_form", "Please fill out my cool form", $this->base_url );

		// We will use wp csrf
		$form->setUseCsrfProtection( false );
		$form->addHiddenField( 'wp-nonce', ValidForm::VFORM_STRING );
		$defaults['wp-nonce'] = wp_create_nonce( $this->nonce_id );

		// ValidFormBuilder Basic Text Field
		$form->addField(
			'test_text_setting',
			"A test setting.",
			ValidForm::VFORM_STRING,
			[
				// Validation
				'required' => true
			],
			[
				// Error message for validation
				'required' => __( "You must include a test setting value.", PLUGIN_CONST_PREFIX_TEXTDOMAIN )
			]
		);

		// ValidFormBuilder Area to group fields
		// http://validformbuilder.org/docs/classes/ValidFormBuilder.Area.html
		// If you set the second parameter to true, the area has a checkbox to enable/disable all fields
		$area = $form->addArea( 'API Connection Credentials', true, $defaults['test_api_group']['is_enabled'] );
		$area->addField( 'api_endpoint', "API Endpoint", ValidForm::VFORM_STRING );
		$area->addField( 'api_key', "API Key", ValidForm::VFORM_STRING );
		$area->addField( 'api_secret', "API_SECRET", ValidForm::VFORM_PASSWORD );

		// ValidFormBuilder Select dropdown
		// http://validformbuilder.org/docs/classes/ValidFormBuilder.Select.html
		$select = $form->addField( 'text_select_box', "Should this Foo or Bar?", ValidForm::VFORM_SELECT_LIST );
		$this->build_field_group( $select, $options['test_select_box'], $defaults['test_select_box'] );

		// ValidFormBuilder Group of Checkboxes
		$checklist = $form->addField( 'test_checkbox_group', "Choose your colors:", ValidForm::VFORM_CHECK_LIST );
		$this->build_field_group( $checklist, $options['test_checkbox_group'], $defaults['test_checkbox_group'] );

		$form->setDefaults( $defaults );

		return $form;
	}

	/**
	 * Build a select option group or a group of checkboxes
	 *
	 * @param $options Array of options
	 * @param $selected The selected option
	 *
	 * @return void
	 */
	private function build_field_group( $group, $options, $selected ) {
		foreach ( $options as $okey => $oval ) {
			$group->addField( $oval, $okey );
		}
	}
}