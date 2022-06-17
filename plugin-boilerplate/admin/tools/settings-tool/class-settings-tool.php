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


class SettingsTool extends AbstractAdminTool {
	protected $title;
	protected $slug = "settings-tool";
	protected $description;

	protected function init() {
		$this->title       = __( "Settings", PLUGIN_CONST_PREFIX_TEXTDOMAIN );
		$this->description = __( "Manage PLUGIN_NAME settings.", PLUGIN_CONST_PREFIX_TEXTDOMAIN );
	}

	/**
	 * @inheritDoc
	 */
	public function render() {
		$this->add_partial( plugin_dir_path( __FILE__ ) . "partials/settings-form.partial.php" );
	}

	/**
	 * Build the settings form.
	 *
	 * Uses the `formr` library (https://github.com/formr/Formr)
	 *
	 * @return void
	 */
	private function build_form() {

	}
}