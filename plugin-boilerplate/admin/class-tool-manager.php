<?php /** @noinspection SpellCheckingInspection */

namespace PLUGIN_PACKAGE\Admin;

use \PLUGIN_PACKAGE\Admin\Tools;

class ToolManager {

	/** @var Tools\AbstractAdminTool[] */
	private $tools;

	public function load() {
		$dir   = \plugin_dir_path( __FILE__ ) . "tools";
		$files = \scandir( $dir );

		foreach ( $files as $file ) {
			$fpath = $dir . "/" . $file;
			if ( is_dir( $fpath ) && substr( $file, 0, 1 ) !== "." ) {
				$tool_class = \ucwords( $file, "-" );
				$tool_class = \str_replace( "-", "", $tool_class );
				$tool_class = __NAMESPACE__ . '\\Tools\\' . $tool_class;
				$tool_file  = "class-" . $file . ".php";
				$tool_path  = $dir . "/" . $file . "/" . $tool_file;

				if ( is_file( $tool_path ) ) {
					require_once( $tool_path );
					$tinst = new $tool_class;
					$this->add_tool( $tinst );
				}
			}
		}
	}

	/**
	 * @param $tool Tools\AbstractAdminTool
	 *
	 * @return void
	 */
	private function add_tool( $tool ) {
		$this->tools[] = $tool;
	}

	public function build_submenu( $main_menu_slug ) {
		foreach ( $this->tools as $t ) {
			$title    = $t->getToolTitle();
			$uri_slug = $t->getToolURISlug();
			$render   = $t->getToolRenderMethod();
			add_submenu_page( $main_menu_slug, $title, $title, PLUGIN_CONST_PREFIX_MIN_ADMIN_CAPABILITY, $uri_slug, $render );
		}
	}

	public function get_tools() {
		return $this->tools;
	}

}