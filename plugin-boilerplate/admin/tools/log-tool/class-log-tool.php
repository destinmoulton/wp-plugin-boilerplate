<?php

/**
 * The Debugger Tool
 *  - Output debug messages to js console
 *  - Log to file
 *
 * @package   PLUGIN_PACKAGE
 * @author    PLUGIN_AUTHOR_NAME <PLUGIN_AUTHOR_EMAIL>
 * @copyright COPYRIGHT
 * @license   PLUGIN_LICENSE_NAME
 * @link      PLUGIN_URI
 */

namespace PLUGIN_PACKAGE\Admin\Tools;

use PLUGIN_PACKAGE;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class LogTool extends AbstractAdminTool {
	protected $slug = "log-tool";

	public function __construct() {
		parent::__construct();

		$this->title       = __( "Log Tool", PLUGIN_CONST_PREFIX_TEXTDOMAIN );
		$this->description = __( "Simple debugging via console or log file.", PLUGIN_CONST_PREFIX_TEXTDOMAIN );
	}

	public function render() {
		/** @var \PLUGIN_PACKAGE\Logger $PLUGIN_FUNC_PREFIX_logger */
		global $PLUGIN_FUNC_PREFIX_logger;
		$redirect = false;
		if ( isset( $_GET["action"] ) ) {
			switch ( $_GET["action"] ) {
				case "test_logging":
					\PLUGIN_FUNC_PREFIX_log( array( "test" => PLUGIN_CONST_PREFIX_NAME . " :: This is a test!" ) );
					if ( $PLUGIN_FUNC_PREFIX_logger->get_log_type() == "file" ) {
						$redirect = true;
					}
					break;
				case "clear_file_log":
					// Clear the log
					file_put_contents( $PLUGIN_FUNC_PREFIX_logger->get_log_file_path(), "" );
					$redirect = true;
					break;
				case "enable_logging":
					$PLUGIN_FUNC_PREFIX_logger->turn_on_logging();
					$redirect = true;
					break;
				case "disable_logging":
					$PLUGIN_FUNC_PREFIX_logger->turn_off_logging();
					$redirect = true;
					break;
				case "log_to_console":
					$PLUGIN_FUNC_PREFIX_logger->set_option( "type", "console" );
					$redirect = true;
					break;
				case "log_to_file":
					$PLUGIN_FUNC_PREFIX_logger->set_option( "type", "file" );
					$redirect = true;
					break;
				default:
					break;
			}
		}

		$query_params = array( "page" => $this->uri_slug );
		$tool_url     = admin_url( 'admin.php?' . http_build_query( $query_params ) );
		if ( $redirect ) {
			require_once( $this->get_path() . "partials/js-redirect.partial.php" );
		} else {
			$logger_is_running  = $PLUGIN_FUNC_PREFIX_logger->is_logging();
			$is_logging_to_file = $PLUGIN_FUNC_PREFIX_logger->is_logging_to_file();
			require_once( $this->get_path() . "partials/log-tool-options.partial.php" );

			if ( $is_logging_to_file ) {
				$logfile_path     = $PLUGIN_FUNC_PREFIX_logger->get_log_file_path();
				$logfile_contents = $PLUGIN_FUNC_PREFIX_logger->get_log_file_contents();
				$logfile_entries  = explode( $PLUGIN_FUNC_PREFIX_logger->logfile_separator, $logfile_contents );
				require_once( $this->get_path() . "partials/log-file.partial.php" );
			}
		}
	}

}
