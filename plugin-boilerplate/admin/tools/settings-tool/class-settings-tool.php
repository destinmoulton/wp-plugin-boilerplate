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
use const PLUGIN_PACKAGE\PLUGIN_CONST_PREFIX_SETTING_DEFAULTS;

class SettingsTool extends AbstractAdminTool {
	protected $title;
	protected $slug = "settings-tool";
	protected $description;
	private $nonce_id;
	private $fields;

	protected function init() {
		$this->title       = __( "Settings", PLUGIN_CONST_PREFIX_TEXTDOMAIN );
		$this->description = __( "Manage PLUGIN_NAME settings.", PLUGIN_CONST_PREFIX_TEXTDOMAIN );
		$this->nonce_id    = "PLUGIN_FUNC_PREFIX_save_settings_form";

		// Enqueue assets and require all the php files
		\PLUGIN_PACKAGE\Form::enqueue_and_require();


		/**
		 * The Settings Fields definition
		 *
		 * The array keys correlates with the settings defined above.
		 *
		 */
		$this->fields = [
			'bootstrap_onoff'  => [
				'type'   => 'area',
				'label'  => 'Load Bootstrap?',
				'toggle' => true, // Whether this area is toggle-able
				'fields' => [
					'bootstrap_js_url'  => [
						'type'  => ValidForm::VFORM_STRING,
						'label' => 'Bootstrap JS CDN URL'
					],
					'bootstrap_css_url' => [
						'type'  => ValidForm::VFORM_STRING,
						'label' => 'Bootstrap CSS CDN URL'
					]
				]
			],
			'example_fieldset' => [
				'type'   => 'fieldset',
				'label'  => 'Test Fields',
				'fields' => [
					'test_select_box'     => [
						'type'    => ValidForm::VFORM_SELECT_LIST,
						'label'   => "Test Select Box",
						'options' => [
							'foo' => 'Foo',
							'bar' => 'Bar',
						]
					],
					'test_checkbox_group' => [
						'type'    => ValidForm::VFORM_CHECK_LIST,
						'label'   => "Test Checkboxes",
						'options' => [
							'red'   => 'Red',
							'blue'  => 'Blue',
							'green' => 'Green'
						]
					]
				]
			]

		];
	}

	/**
	 * @inheritDoc
	 */
	public function render() {

		$form = $this->setup_form();

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
	private function setup_form() {
		$values = \PLUGIN_PACKAGE\Settings::get_all();

		$form = \PLUGIN_PACKAGE\Form::create(
			"PLUGIN_FUNC_PREFIX_settings_form",
			"PLUGIN_NAME Settings",
			$this->base_url,
			$this->nonce_id
		);

		$form = \PLUGIN_PACKAGE\Form::add_fields( $form, $this->fields, $values );

		return $form;
	}

}