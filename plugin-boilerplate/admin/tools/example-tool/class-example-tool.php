<?php

namespace PLUGIN_PACKAGE\Admin\Tools;

class ExampleTool extends AbstractAdminTool {
	protected $title = "ExampleTool";
	protected $slug = "example-tool";
	protected $description = "Example tool that outlines the admin functionality.";

	/**
	 * @inheritDoc
	 */
	public function render() {
		require_once(plugin_dir_path( __FILE__ )."/partials/example-tool.partial.php");
	}
}