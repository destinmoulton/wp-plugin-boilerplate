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

use PLUGIN_PACKAGE\Logger;
use PLUGIN_PACKAGE;
use \ValidFormBuilder;
use \ValidFormBuilder\ValidForm;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class LogTool extends AbstractAdminTool {
	protected $slug = "log-tool";
	protected $tabs = [];

	private $should_js_redirect = false;

	protected function init() {
		/** @var \PLUGIN_PACKAGE\Logger $PLUGIN_FUNC_PREFIX_logger */
		global $PLUGIN_FUNC_PREFIX_logger;
		$this->title       = __( "Log Tool", PLUGIN_CONST_PREFIX_TEXTDOMAIN );
		$this->description = __( "Simple debugging via console or log file.", PLUGIN_CONST_PREFIX_TEXTDOMAIN );

		$this->add_tab(
			'settings',
			__( 'Log Settings', PLUGIN_CONST_PREFIX_TEXTDOMAIN ),
			[ $this, "tab_settings" ]
		);
		$this->add_tab(
			'logfile',
			__( 'Log File', PLUGIN_CONST_PREFIX_TEXTDOMAIN ),
			[ $this, "tab_logfile" ]
		);

		if ( $this->is_active() ) {
			$this->actions();
		}
	}

	private function actions() {
		if ( isset( $_GET["action"] ) ) {
			switch ( $_GET["action"] ) {
				case "enable_logging":
					$PLUGIN_FUNC_PREFIX_logger->enable_user_logging();
					$this->redirect( $this->base_url );
					break;
				case "disable_logging":
					$PLUGIN_FUNC_PREFIX_logger->disable_user_logging();
					$this->redirect( $this->base_url );
					break;
				case "reset_logging":
					$PLUGIN_FUNC_PREFIX_logger->reset_user_logging();
					$this->redirect( $this->base_url );
					break;
				default:
					break;
			}
		}
	}

	public function render() {
		$this->route_tabs();
	}

	public function tab_settings() {
		global $PLUGIN_FUNC_PREFIX_logger;
		$form = $this->build_settings_form();
		if ( isset( $_GET["action"] ) ) {
			switch ( $_GET["action"] ) {
				case "test_logging":
					\PLUGIN_FUNC_PREFIX_log( array( "test" => PLUGIN_CONST_PREFIX_NAME . " :: This is a test!" ) );
					break;
				case "clear_file_log":
					$PLUGIN_FUNC_PREFIX_logger->clear_log_file();
					break;
				case "process_form":
					$this->save_log_settings( $form );
					break;
				default:
					break;
			}
		}

		$pdata                       = [];
		$pdata['logger_is_running']  = $PLUGIN_FUNC_PREFIX_logger->is_logging();
		$pdata['is_logging_to_file'] = $PLUGIN_FUNC_PREFIX_logger->is_logging_to_file();

		$pdata['form_html'] = "";
		if ( $PLUGIN_FUNC_PREFIX_logger->is_logging() ) {
			$pdata['form_html'] = $form->toHtml();
		}
		$this->add_partial( $this->get_path() . "partials/log-tool-options.partial.php", $pdata );

	}

	public function tab_logfile() {
		global $PLUGIN_FUNC_PREFIX_logger;
		$logfile_path             = $PLUGIN_FUNC_PREFIX_logger->get_log_file_path();
		$logfile_contents         = $PLUGIN_FUNC_PREFIX_logger->get_log_file_contents();
		$pdata['logfile_path']    = $logfile_path;
		$pdata['logfile_entries'] = explode( $PLUGIN_FUNC_PREFIX_logger->logfile_separator, $logfile_contents );
		$this->add_partial( $this->get_path() . "partials/log-file.partial.php", $pdata );
	}

	private function build_settings_form() {
		global $PLUGIN_FUNC_PREFIX_logger;
		if ( $PLUGIN_FUNC_PREFIX_logger->is_logging() ) {
			$options = $PLUGIN_FUNC_PREFIX_logger->get_options();
			$prefix  = 'log_tool_';
			$form    = new ValidForm( $prefix . "_options", "Logging Options", $this->base_url . "&action=process_form" );

			// We will use wp csrf
			$form->setUseCsrfProtection( false );
			$form->addHiddenField( 'wp-nonce', ValidForm::VFORM_STRING );
			$defaults['wp-nonce'] = wp_create_nonce( $prefix . "nonce" );

			$logto    = $form->addField( 'log_to', "Log To:", ValidForm::VFORM_CHECK_LIST, array( 'required' => true ) );
			$logtoops = [ 'console' => 'JS Console', 'file' => 'File' ];
			$this->build_field_group( $logto, $logtoops, $options['log_to'] );

			$form->addField( "file_name", "File name (without extension):", ValidForm::VFORM_STRING );

			$ftype = $form->addField( "file_type", "File log type:", ValidForm::VFORM_RADIO_LIST );
			$fops  = [ 'log' => 'Log', 'html' => 'HTML' ];

			$this->build_field_group( $ftype, $fops, [ $options['file']['type'] ] );

			$phparea   = $form->addArea( 'Enable PHP Error Handling?', $options['php']['log_php_errors'], "php_log_php_errors" );
			$checklist = $phparea->addField( "php_error_levels", "PHP Error Levels", ValidForm::VFORM_CHECK_LIST );
			foreach ( $PLUGIN_FUNC_PREFIX_logger->get_php_exceptions() as $ekey => $ename ) {
				$selected = false;
				if ( in_array( $ename, $options['php']['error_reporting'] ) ) {
					$selected = true;
				}
				$checklist->addField( $ename, $ename, $selected );
			}
		}

		return $form;
	}

	private function build_field_group( $group, $options, $sel ) {
		foreach ( $options as $okey => $oval ) {
			$selected = false;
			if ( in_array( $okey, $sel ) ) {
				$selected = true;
			}
			$group->addField( $oval, $okey, $selected );
		}
	}

	private function save_log_settings( $form ) {
		global $PLUGIN_FUNC_PREFIX_logger;
		$new = $PLUGIN_FUNC_PREFIX_logger->get_default_options();

		if ( $PLUGIN_FUNC_PREFIX_logger->is_logging() ) {
			$new['enabled'] = 1;
		}

		if ( ! isset( $_POST['log_to'] ) ) {
			$new['log_to'] = [];
		} else {
			$new['log_to'] = $_POST['log_to'];
		}
		if ( ! isset( $_POST['php_log_php_errors'] ) || ! $_POST['php_log_php_errors'] ) {
			$new['php']['log_php_errors'] = 0;
		} elseif ( $_POST['php_log_php_errors'] == 'on' ) {
			$new['php']['log_php_errors'] = 1;
		}

		if ( ! isset( $_POST['php_error_levels'] ) ) {
			$new['php']['error_reporting'] = [];
		} else {

			$new['php']['error_reporting'] = $_POST['php_error_levels'];
		}
		exit();
	}

}
