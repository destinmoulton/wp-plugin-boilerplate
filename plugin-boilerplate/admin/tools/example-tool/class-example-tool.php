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

	protected function init() {
		$this->title       = __( "Example Tool", PLUGIN_CONST_PREFIX_TEXTDOMAIN );
		$this->description = __( "Simple example tool. Copy this folder ('admin/tools/example-tool') to make your own tool.", PLUGIN_CONST_PREFIX_TEXTDOMAIN );
	}

	/**
	 * This function is called when the menu option is clicked.
	 *
	 * Put your tool routing in this function.
	 *
	 * @inheritDoc
	 */
	public function render() {
		// You can add notices that will display in the header
		\PLUGIN_PACKAGE\Notices::success( "Example successful notification." );

		// Functionality can be added via query parameters
		// In this case we use an 'action' in the url
		if ( isset( $_GET['action'] ) ) {
			switch ( $_GET['action'] ) {
				case 'do_something':
					// Do stuff...
					\PLUGIN_PACKAGE\Notices::info( "An action via query parameter was just performed." );
					break;
				default:
					break;
			}
		}

		// Add variables to the partial template via
		// a keyed array. The key will be extracted
		// into the variable.
		$pdata = [
			'hello_world' => "Hello, world!"
		];
		$this->add_partial( plugin_dir_path( __FILE__ ) . "partials/example-tool.partial.php", $pdata );
	}
}