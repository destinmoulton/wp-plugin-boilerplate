<?php

/**
 * PLUGIN_NAME Logger
 *  - JS Console or File Logging
 *  - Log just for single user
 *  - WP User Meta to store the options
 *
 * @package   PLUGIN_PACKAGE
 * @author    PLUGIN_AUTHOR_NAME <PLUGIN_AUTHOR_EMAIL>
 * @copyright COPYRIGHT
 * @license   PLUGIN_LICENSE_NAME
 * @link      PLUGIN_URI
 */

namespace PLUGIN_PACKAGE;

class Logger {

	/** @var array */
	private $log_entries = [];

	/** @var array */
	private $options = [];

	/** @var bool */
	private $is_enabled = false;

	/** @var bool */
	private $is_hydrated = false;

	/** @var string */
	private $user_meta_key = "PLUGIN_FUNC_PREFIX_logger_options";

	/** @var array */

	function __construct() {

		if ( ! \PLUGIN_FUNC_PREFIX_has_permissions( 'logger' ) ) {
			return;
		}

		// Is the user already configured?
		$this->hydrate();

		if ( $this->is_enabled ) {
			if ( $this->is_log_type_enabled( 'console' ) ) {
				// Queue the console output to run in the footer
				add_action( 'wp_footer', [ $this, "wp_action_output_console" ], 100 );
				add_action( 'admin_footer', [ $this, "wp_action_output_console" ], 100 );
			}

			// Override the PHP error reporting
			if ( $this->options['php']['log_php_errors'] == 1 ) {
				error_reporting( E_ALL );
				//ini_set("display_errors", 1);
				set_error_handler( [ $this, "php_error_handler" ] );
			}
		}
	}

	public function __destruct() {
		if ( $this->is_logging_to_file() ) {
			$this->output_logfile();
		}
	}

	public function php_error_handler( $errno, $errstr, $errfile = "", $errline = "" ) {
		$data           = [];
		$data['source'] = 'php';

		switch ( $errno ) {
			case E_USER_ERROR:
				$data['level'] = 'error';
				$msg           = "PHP E_USER_ERROR [$errno]\n\n$errstr\n\n";
				break;
			case E_USER_WARNING:
				$data['level'] = 'warn';
				$msg           = "PHP E_USER_WARNING [$errno]\n\n$errstr\n\n";
				break;
			case E_USER_NOTICE:
				$data['level'] = 'info';
				$msg           = "PHP E_USER_NOTICE [$errno]\n\n$errstr\n\n";
				break;
			case E_USER_DEPRECATED:
				$data['level'] = 'log';
				$msg           = "PHP E_USER_DEPRECATED [$errno]\n\n$errstr\n\n";
				break;
			case E_ERROR:
				$data['level'] = 'error';
				$msg           = "PHP E_ERROR [$errno]\n\n$errstr\n\n";
				break;
			case E_WARNING:
				$data['level'] = 'warn';
				$msg           = "PHP E_WARNING [$errno]\n\n$errstr\n\n";
				break;
			case E_NOTICE:
				$data['level'] = 'info';
				$msg           = "PHP E_NOTICE [$errno]\n\n$errstr\n\n";
				break;
			case E_DEPRECATED:
				$data['level'] = 'log';
				$msg           = "PHP E_DEPRECATED [$errno]\n\n$errstr\n\n";
				break;
			default:
				$data['level'] = 'log';
				$msg           = "PHP UNTRACKED [$errno]\n\n$errstr\n\n";
				break;
		}
		$backtrace = debug_backtrace();
		array_shift( $backtrace );
		if ( $this->options['backtrace'] == 1 ) {
			// Remove the mention of this function from the backtrace
			$data['backtrace'] = print_r( $backtrace, true );
		} else {
			$data['backtrace'] = "";
		}

		$data['args'][]         = $msg;
		$data['unix_timestamp'] = time();
		if ( isset( $backtrace[0]['file'] ) ) {
			$data['location'] = [
				'file' => $backtrace[0]['file'],
				'line' => $backtrace[0]['line']
			];
		} else {
			$data['location'] = [
				'file' => "",
				'line' => ""
			];
		}
		$this->log_entries[] = $data;
	}

	/**
	 * If the first parameter is 'error' 'warn'
	 * or 'info', that will set the type of
	 * the log entry.
	 *
	 * @return void
	 */
	public function log() {

		if ( ! $this->is_enabled ) {
			return;
		}

		$logargs = func_get_args();

		$data = [];

		$backtrace = debug_backtrace();

		// Remove the mention of this function from the backtrace
		array_shift( $backtrace );

		if ( $this->options['backtrace'] == 1 ) {
			$data['backtrace'] = print_r( $backtrace, true );
		} else {
			$data['backtrace'] = "";
		}

		$data['args']     = [];
		$data['level']    = 'log';
		$data['source']   = 'user';
		$data['location'] = [
			'file' => $backtrace[0]['file'],
			'line' => $backtrace[0]['line']
		];

		$data['unix_timestamp'] = time();

		if ( in_array( $logargs[0], [ 'error', 'warn', 'info', 'log' ] ) ) {
			$data['level'] = $logargs[0];
		}
		foreach ( $logargs as $arg ) {
			if ( is_array( $arg ) ) {
				$data['args'][] = print_r( $arg, true );
			} elseif ( is_object( $arg ) ) {
				$data['args'][] = print_r( $arg, true );
			} else {
				$data['args'][] = $arg;
			}
		}

		if ( ! isset( $this->log_entries ) ) {
			$this->log_entries = array();
		}
		$this->log_entries[] = $data;
	}


	/**
	 * Check user meta for logger settings.
	 *
	 * @return void
	 */
	private function hydrate() {
		$this->options    = [];
		$this->is_enabled = false;

		$user_meta = $this->_get_user_meta();

		if ( is_array( $user_meta ) ) {
			if ( $this->are_options_valid( $user_meta ) ) {
				$this->is_hydrated = true;
				$this->options     = $user_meta;
				if ( $this->options['enabled'] == 1 ) {
					$this->is_enabled = true;
				}
			}
		}
	}


	/**
	 * @param $section
	 * @param $key
	 * @param $value
	 *
	 * @return bool
	 */
	public function set_option( $section, $key, $value ) {
		if ( ! isset( $this->options[ $section ] ) || ! isset( $this->options[ $section ][ $key ] ) ) {
			return false;
		}
		$this->options[ $section ][ $key ] = $value;
		$this->_set_user_meta();

		return true;
	}


	/**
	 * Set the user meta options and overwrite
	 * this instance's set.
	 *
	 * @param $newoptions
	 *
	 * @return bool
	 */
	public function set_options( $newoptions ) {
		if ( ! isset( $newoptions['enabled'] ) ) {
			$newoptions['enabled'] = $this->options['enabled'];
		}
		if ( $this->are_options_valid( $newoptions ) ) {
			$this->options = $newoptions;
			$res           = $this->_set_user_meta();

			return ! ( $res == false );
		}

		return false;
	}

	/**
	 * Enable logging with the default options.
	 *
	 * @return void
	 */
	public function enable_user_logging() {
		if ( ! PLUGIN_FUNC_PREFIX_has_permissions( 'logger' ) ) {
			return;
		}
		if ( ! $this->is_hydrated ) {
			$this->hydrate();

			if ( ! $this->is_hydrated ) {
				// No user meta found/hydrated; use defaults
				$this->options = PLUGIN_CONST_PREFIX_LOGGER_DEFAULT_SETTINGS;
			}
		}

		$this->is_enabled         = true;
		$this->options['enabled'] = 1;
		$this->_set_user_meta();
		$this->is_hydrated = true;
	}

	/**
	 * Disable logging. Keeps other options in meta.
	 *
	 * @return void
	 */
	public function disable_user_logging() {
		$this->log_entries        = array();
		$this->options['enabled'] = 0;
		$this->is_enabled         = false;
		$this->_set_user_meta();
	}

	/**
	 * Reset logging options to defaults.
	 *
	 * @return void
	 */
	public function reset_user_logging() {
		$this->log_entries = array();
		$this->options     = PLUGIN_CONST_PREFIX_LOGGER_DEFAULT_SETTINGS;
		$this->_set_user_meta();
	}

	private function _get_user_meta() {
		$meta = get_user_meta( get_current_user_id(), $this->user_meta_key );
		if ( is_array( $meta ) && isset( $meta[0] ) ) {
			return $meta[0];
		}

		return [];
	}

	/**
	 * Set the user meta settings for the logger.
	 *
	 * @return bool|int
	 */
	public function _set_user_meta() {
		$meta = $this->_get_user_meta();
		if ( is_array( $meta ) && isset( $meta['enabled'] ) ) {
			return update_user_meta( get_current_user_id(), $this->user_meta_key, $this->options );
		} else {
			return add_user_meta( get_current_user_id(), $this->user_meta_key, $this->options, true );
		}
	}

	private function _delete_user_meta() {
		delete_user_meta( get_current_user_id(), $this->user_meta_key );
	}


	/**
	 * @return string
	 */
	public function get_log_file_path() {
		return $this->options['file']['dir'] . "/" . $this->options['file']['name'];
	}

	/**
	 * @return string
	 */
	public function get_log_file_url() {
		return site_url( $this->options['file']['uri_path'] . $this->options['file']['name'] );
	}

	/**
	 * @return bool
	 */
	public function is_logging(): bool {
		return $this->is_enabled;
	}

	/**
	 * @return bool
	 */
	public function is_logging_to_file() {
		return $this->is_enabled && $this->is_log_type_enabled( 'file' );
	}

	public function is_log_type_enabled( $type ) {
		return $this->is_enabled && isset( $this->options['log_to'] ) && in_array( $type, $this->options['log_to'] );
	}

	/**
	 * @return array
	 */
	public function get_options() {
		return $this->options;
	}


	/**
	 * Generate Javascript Console Commands
	 *
	 * @return void
	 */
	public function wp_action_output_console() {
		$timezone = wp_timezone_string();
		?>
        <script>
            (function () {


                console.log("%cPLUGIN_PACKAGE Plugin - Console Log is Enabled.", "color:green;");
				<?php
				if ($this->is_logging_to_file()) {
				?>
                console.groupCollapsed("%cPLUGIN_PACKAGE Plugin - File Log is Enabled", "color:darkorange;");
                console.log("<?=$this->get_log_file_path()?>");
                console.log("<?=$this->get_log_file_url()?>");
                console.groupEnd();
				<?
				}
				if(is_array( $this->log_entries )) {
				foreach ($this->log_entries as $idx=>$log) {

				// The title should include the first part of the logged data
				$title = substr( $log['args'][0], 0, 60 );

				// Remove all extra whitespace
				$title = preg_replace( '/\s+/', ' ', $title );

				// Make the title of the group a bit prettier
				$title = preg_replace( '/\v+/', '', $title );

				$style = "color:" . PLUGIN_CONST_PREFIX_LOGGER_CONSOLE_COLORS['default'];
				if ( isset( PLUGIN_CONST_PREFIX_LOGGER_CONSOLE_COLORS[ $log['level'] ] ) ) {
					$style = "color:" . PLUGIN_CONST_PREFIX_LOGGER_CONSOLE_COLORS[ $log['level'] ];
				}

				// Convert UTC to WP timezone
				$dt = new \DateTime( null, new \DateTimeZone( "UTC" ) );
				$dt->setTimestamp( $log['unix_timestamp'] );
				$dt->setTimezone( new \DateTimeZone( $timezone ) );

				$date = $dt->format( $this->options['console']['date_format'] );
				$source = strtoupper( $log['source'] );
				$level = strtoupper( $log['level'] );
				?>
                console.groupCollapsed(`%c<?= $date . " [" . $source . "] [" . $level . "] " . $title ?>...`, '<?=$style?>');
                console.log("<?= $log['location']['file']?>:<?= $log['location']['line']?>");
				<?php foreach ($log['args'] as $arg):?>
                console['<?=$log['level']?>'](`<?=$arg?>`);
				<?php endforeach;?>

				<?php if($this->options['backtrace'] == 1 && $log['backtrace'] != ""):?>
                console.groupCollapsed(`[PHP BACKTRACE]`);
                console.log(`<?=$log['backtrace']?>`);
                console.groupEnd();
				<?php endif;?>

                console.groupEnd();
				<?php
				}
				}
				?>
            })()
        </script>
		<?php
	}

	private function output_logfile() {

		if ( ! $this->is_logging() ) {
			return;
		}
		$txt      = "";
		$timezone = wp_timezone_string();
		if ( isset( $this->log_entries ) ) {
			foreach ( $this->log_entries as $log ) {
				// Convert UTC to WP timezone
				$dt = new \DateTime( null, new \DateTimeZone( "UTC" ) );
				$dt->setTimestamp( $log['unix_timestamp'] );
				$dt->setTimezone( new \DateTimeZone( $timezone ) );

				$source = strtoupper( $log['source'] );
				$level  = strtoupper( $log['level'] );
				$txt    .= $this->options['file']['separator'];
				$txt    .= $dt->format( $this->options['file']['date_format'] );
				$txt    .= " [" . $source . "] [" . $level . "] ";
				$txt    .= PHP_EOL;
				$txt    .= $log['location']['file'] . ":" . $log['location']['line'];
				$txt    .= PHP_EOL;

				foreach ( $log['args'] as $arg ) {
					$txt .= $arg;
					$txt .= PHP_EOL . PHP_EOL;
				}
			}
		}

		file_put_contents( $this->get_log_file_path(), $txt, FILE_APPEND );
	}

	/**
	 * @return string
	 */
	public function get_log_file_contents() {
		return file_get_contents( $this->get_log_file_path() );
	}

	/**
	 * Test the keys and values of the default_options
	 * exist in the $test
	 *
	 * @param $test Options to test
	 *
	 * @return bool
	 */
	public function are_options_valid( $test ) {
		foreach ( PLUGIN_CONST_PREFIX_LOGGER_DEFAULT_SETTINGS as $dskey => $dsopt ) {
			if ( ! isset( $test[ $dskey ] ) ) {
				return false;
			}
		}

		return true;
	}

	public function get_default_options() {
		return PLUGIN_CONST_PREFIX_LOGGER_DEFAULT_SETTINGS;
	}

	public function clear_log_file() {
		// Clear the log
		file_put_contents( $this->get_log_file_path(), "" );
	}
}
