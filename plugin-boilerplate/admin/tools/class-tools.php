<?php /** @noinspection SpellCheckingInspection */

namespace PLUGIN_PACKAGE\Admin\Tools;

class Tools {
	/** @var AbstractAdminTool[] */
	private $tools;

	public function load() {
		$dir = \plugin_dir_path(__FILE__)."/tools";
		$tools = \scandir($dir);

		foreach ( $tools as $tool ) {
			if(is_dir($tool)){
				$tool_class = \ucwords($tool, "-");
				$tool_class = \str_replace("-", "", $tool_class);
				$tool_file = "class-".$tool.".php";
				$tool_path = $dir."/".$tool."/".$tool_file;

				if(is_file($tool_path)){
					require_once($tool_path);
					$tinst = new \PLUGIN_PACKAGE\Admin\Tools\{$tool_class};
					$this->add_tool($tinst);
				}
			}
		}
	}

	/**
	 * @param $tool AbstractAdminTool
	 *
	 * @return void
	 */
	private function add_tool( AbstractAdminTool $tool){
		$this->tools[]	= $tool;
	}

}