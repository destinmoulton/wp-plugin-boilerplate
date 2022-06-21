<?php /** @noinspection SpellCheckingInspection */

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

	private $should_js_redirect = false;

	protected function init() {
		$this->title       = __( "Log Tool", PLUGIN_CONST_PREFIX_TEXTDOMAIN );
		$this->description = __( "Simple debugging via console or log file.", PLUGIN_CONST_PREFIX_TEXTDOMAIN );

		global $PLUGIN_FUNC_PREFIX_logger;
		if ( isset( $_GET["action"] ) ) {
			/**
			 * IMPORTANT! These actions must be done
			 * during init so the cookie can be set
			 */
			switch ( $_GET["action"] ) {
				case "enable_logging":
					$PLUGIN_FUNC_PREFIX_logger->turn_on_logging();
					$this->should_js_redirect = true;
					break;
				case "disable_logging":
					$PLUGIN_FUNC_PREFIX_logger->turn_off_logging();
					$this->should_js_redirect = true;
					break;
				case "log_to_console":
					$PLUGIN_FUNC_PREFIX_logger->set_option( "type", "console" );
					$this->should_js_redirect = true;
					break;
				case "log_to_file":
					$PLUGIN_FUNC_PREFIX_logger->set_option( "type", "file" );
					$this->should_js_redirect = true;
					break;
				default:
					break;
			}
		}
	}

	public function render() {
		/** @var \PLUGIN_PACKAGE\Logger $PLUGIN_FUNC_PREFIX_logger */
		global $PLUGIN_FUNC_PREFIX_logger;
		if ( isset( $_GET["action"] ) ) {
			switch ( $_GET["action"] ) {
				case "test_logging":
					\PLUGIN_FUNC_PREFIX_log( array( "test" => PLUGIN_CONST_PREFIX_NAME . " :: This is a test!" ) );
					if ( $PLUGIN_FUNC_PREFIX_logger->get_log_type() == "file" ) {
						$this->should_js_redirect = true;
					}
					break;
				case "clear_file_log":
					// Clear the log
					file_put_contents( $PLUGIN_FUNC_PREFIX_logger->get_log_file_path(), "" );
					$this->should_js_redirect = true;
					break;
				default:
					break;
			}
		}

		$pdata = [];
		if ( $this->should_js_redirect ) {
			// Do a js redirect so that the cookie
			// is set
			$this->redirect( $this->base_url, true );
		} else {
			$pdata['logger_is_running']  = $PLUGIN_FUNC_PREFIX_logger->is_logging();
			$pdata['is_logging_to_file'] = $PLUGIN_FUNC_PREFIX_logger->is_logging_to_file();

			$this->add_partial( $this->get_path() . "partials/log-tool-options.partial.php", $pdata );

			if ( $pdata['is_logging_to_file'] ) {
				$logfile_path             = $PLUGIN_FUNC_PREFIX_logger->get_log_file_path();
				$logfile_contents         = $PLUGIN_FUNC_PREFIX_logger->get_log_file_contents();
				$pdata['logfile_path']    = $logfile_path;
				$pdata['logfile_entries'] = explode( $PLUGIN_FUNC_PREFIX_logger->logfile_separator, $logfile_contents );
				$this->add_partial( $this->get_path() . "partials/log-file.partial.php", $pdata );
			}
		}
	}

}
