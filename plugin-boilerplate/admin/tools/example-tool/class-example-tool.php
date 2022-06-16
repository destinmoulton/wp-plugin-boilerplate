<?php

namespace PLUGIN_PACKAGE\Admin\Tools;

class ExampleTool extends AbstractAdminTool {
	protected $title = "ExampleTool";
	protected $slug = "example-tool";
	protected $description = "Example tool that outlines the admin functionality.";

	public function __construct() {
		parent::__construct();

		$this->title       = __( "Example Tool", PLUGIN_CONST_PREFIX_TEXTDOMAIN );
		$this->description = __( "Simple debugging via console or log file.", PLUGIN_CONST_PREFIX_TEXTDOMAIN );
	}

	/**
	 * @inheritDoc
	 */
	public function render() {
		require_once( plugin_dir_path( __FILE__ ) . "partials/example-tool.partial.php" );
	}
}