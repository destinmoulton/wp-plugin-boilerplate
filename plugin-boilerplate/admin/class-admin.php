<?php /** @noinspection SpellCheckingInspection */

/**
 * Initialize and load the admin section.
 *
 * Tools should be placed in the top level `tools`
 * directory.
 *
 * @package   PLUGIN_PACKAGE
 * @author    PLUGIN_AUTHOR_NAME <PLUGIN_AUTHOR_EMAIL>
 * @copyright COPYRIGHT
 * @license   PLUGIN_LICENSE_NAME
 * @link      PLUGIN_URI
 */

namespace PLUGIN_PACKAGE\Admin;


class Admin {
	/** @var ToolManager */
	private $toolmanager;
	private $menu_slug = PLUGIN_CONST_PREFIX_SLUG;

	public function run() {

		// Load the tool functions
		require_once( PLUGIN_CONST_PREFIX_PLUGIN_ROOT . "admin/functions/tools.functions.php" );
		// Abstract Tool that all Tools inherit from
		require_once( PLUGIN_CONST_PREFIX_PLUGIN_ROOT . "admin/tools/class-abstract-admin-tool.php" );
		// Tool Manager loads the tools
		require_once( PLUGIN_CONST_PREFIX_PLUGIN_ROOT . "admin/class-tool-manager.php" );
		$this->toolmanager = new ToolManager();
		$this->toolmanager->load();

		add_action( "admin_menu", array( $this, "build_menu" ) );
	}

	/**
	 * MUST BE public for action to work!
	 * @return void
	 */
	public function build_menu() {
		add_menu_page( PLUGIN_CONST_PREFIX_NAME, PLUGIN_CONST_PREFIX_NAME, PLUGIN_CONST_PREFIX_MIN_ADMIN_CAPABILITY, $this->menu_slug, array(
			$this,
			"render"
		) );
		$this->toolmanager->build_submenu( $this->menu_slug );
	}

	/**
	 * The default render method when the PLUGIN_NAME menu item is clicked.
	 *
	 * NOTE: Must be public.
	 * @return void
	 */
	public function render() {
		echo "<h3>" . PLUGIN_CONST_PREFIX_NAME . "</h3>";
		echo "<ul>";
		$tools = $this->toolmanager->get_tools();
		foreach ( $tools as $t ) {

			$title       = $t->get_title();
			$uri_slug    = $t->get_uri_slug();
			$description = $t->get_description();
			$url         = admin_url( 'admin.php?page=' . $uri_slug );
			echo "<li><a href='" . $url . "'>" . $title . "</a><br/><em>" . $description . "</em></li>";
		}
		echo "</ul>";
	}
}