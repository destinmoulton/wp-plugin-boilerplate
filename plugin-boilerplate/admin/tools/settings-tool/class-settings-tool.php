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
	private $form;

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
			'bootstrap_enabled' => [
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
			'example_fieldset'  => [
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

		$this->form = $this->setup_form();
		$this->actions();
	}

	private function actions() {
		if ( isset( $_GET['action'] ) ) {
			if ( $_GET['action'] == 'submit' ) {
				if ( \PLUGIN_PACKAGE\Form::is_submitted_and_valid( $this->form, $this->nonce_id ) ) {
					$values = \PLUGIN_PACKAGE\Form::get_submitted_values( $this->form, $this->fields, \PLUGIN_PACKAGE\PLUGIN_CONST_PREFIX_SETTING_DEFAULT_VALUES, \PLUGIN_PACKAGE\Settings::get_all() );
					\PLUGIN_PACKAGE\Settings::set_all( $values );
					$this->redirect( $this->base_url );
				}
			}
		}
	}

	/**
	 * @inheritDoc
	 */
	public function render() {
		$pvars = [
			'form_html' => $this->form->toHtml()
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
			$this->base_url . "&action=submit",
			$this->fields,
			$values,
			$this->nonce_id
		);


		return $form;
	}

}