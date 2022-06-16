<?php

/**
 * An Example Tool
 *
 * @package   PLUGIN_PACKAGE
 * @author    PLUGIN_AUTHOR_NAME <PLUGIN_AUTHOR_EMAIL>
 * @copyright COPYRIGHT
 * @license   PLUGIN_LICENSE_NAME
 * @link      PLUGIN_URI
 */

namespace PLUGIN_PACKAGE\Admin\Tools;

class ExampleTool extends AbstractAdminTool {
	protected $title;
	protected $slug = "example-tool";
	protected $description;

	public function __construct() {
		parent::__construct();

		$this->title       = __( "Example Tool", PLUGIN_CONST_PREFIX_TEXTDOMAIN );
		$this->description = __( "Simple example tool. Does nothing really.", PLUGIN_CONST_PREFIX_TEXTDOMAIN );
	}

	/**
	 * @inheritDoc
	 */
	public function run() {
		require_once( plugin_dir_path( __FILE__ ) . "partials/example-tool.partial.php" );
	}
}