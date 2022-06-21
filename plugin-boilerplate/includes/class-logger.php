<?php

/**
 * PLUGIN_NAME Logger
 *  - JS Console or File Logging
 *  - Uses cookies to enable and configure
 *    Why cookies? So you can enable/disable it *without*
 *    using the Logger tool.
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
	private $log;

	/** @var array */
	private $options;

	/** @var array */
	private $default_options;

	/** @var bool */
	private $is_configured;

	/** @var int */
	private $cookie_expiration_offset;

	/** @var string */
	private $cookie_path;

	/** @var string */
	private $cookie_name;

	/** @var string */
	public $logfile_separator; // public so that the parser can explode it

	function __construct() {

		$this->is_configured            = false;
		$this->options                  = array();
		$this->log                      = array();
		$this->cookie_expiration_offset = 60 * 60 * 24 * 30; // 30 days
		$this->cookie_path              = "/";
		$this->cookie_name              = "PLUGIN_FUNC_PREFIX_logger";

		$this->default_options   = array(
			"status"    => "enabled",
			"type"      => "console", // either 'console' or 'file'
			"file_name" => "PLUGIN_FUNC_PREFIX.log",
			"file_dir"  => WP_CONTENT_DIR,
		);
		$this->logfile_separator = "----------------------------------------------";

		// NOTE: Only setup the log tool if there is a debug cookie
		if ( isset( $_COOKIE[ $this->cookie_name ] ) ) {
			$this->configure();
			if ( $this->is_configured && $this->options['type'] == "console" ) {
				// Queue the console output to run in the footer
				// TODO: Change to local method call [$this, 'method_name']
				add_action( 'wp_footer', [ $this, "wp_action_output_console" ], 100 );
				add_action( 'admin_footer', [ $this, "wp_action_output_console" ], 100 );
			}
		}
	}

	public function __destruct() {
		if ( $this->is_logging_to_file() ) {
			$this->output_logfile();
		}
	}

	public function log() {

		if ( ! $this->is_configured ) {
			return;
		}

		$logargs = func_get_args();

		$backtrace = debug_backtrace();

		// Remove the mention of this function from the backtrace
		array_shift( $backtrace );

		$data              = array();
		$data['backtrace'] = print_r( $backtrace, true );
		$data['args']      = array();

		foreach ( $logargs as $arg ) {
			if ( is_array( $arg ) ) {
				$data['args'][] = print_r( $arg, true );
			} elseif ( is_object( $arg ) ) {
				$data['args'][] = print_r( $arg, true );
			} else {
				$data['args'][] = $arg;
			}
		}

		if ( ! isset( $this->log ) ) {
			$this->log = array();
		}
		$this->log[] = $data;
	}


	/**
	 * This is sort of weird.
	 *
	 * The cookie (ie PLUGIN_FUNC_PREFIX_logger) can be set to "enable"
	 * on the client. This will automatically enable the
	 * logger. This means that logging can be enabled *without* a constant.
	 *
	 * @return void
	 */
	private function configure() {

		$this->options       = array();
		$this->is_configured = false;
		if ( isset( $_COOKIE[ $this->cookie_name ] ) ) {
			if ( $_COOKIE[ $this->cookie_name ] == "start" || $_COOKIE[ $this->cookie_name ] == "enable" ) {
				$this->turn_on_logging();
			} else if ( $_COOKIE[ $this->cookie_name ] == "stop" || $_COOKIE[ $this->cookie_name ] == "disable" ) {
				$this->turn_off_logging();
			} else {
				$cookie  = stripslashes( $_COOKIE[ $this->cookie_name ] );
				$options = $this->parse_cookie( $cookie );

				// Validate that the options are set
				foreach ( $this->default_options as $okey => $op ) {
					if ( ! isset( $options[ $okey ] ) ) {
						return;
					}
				}

				if ( isset( $options['status'] ) && $options['status'] == "enabled" ) {
					$this->options       = $options;
					$this->is_configured = true;
				}
			}
		}
	}


	/**
	 * @param $key
	 * @param $value
	 *
	 * @return void
	 */
	public function set_option( $key, $value ) {
		$this->options[ $key ] = $value;
		$this->setup_cookie_from_options();
	}

	/**
	 * Enable logging. Create and populate on the cookie.
	 * @return void
	 */
	public function turn_on_logging() {
		$this->options       = $this->default_options;
		$this->is_configured = true;
		$this->setup_cookie_from_options();
	}

	/**
	 * Disable logging. Expire the cookie.
	 *
	 * @return void
	 */
	public function turn_off_logging() {
		$this->log           = array();
		$this->options       = array();
		$this->is_configured = false;
		// Expire the cookie
		$this->set_cookie( "", time() - $this->cookie_expiration_offset );
	}

	private function setup_cookie_from_options() {
		$expires = time() + $this->cookie_expiration_offset;
		$cookie  = $this->encode_cookie( $this->options );
		$this->set_cookie( $cookie, $expires );
	}

	private function set_cookie( $cookie, $expires ) {
		setcookie( $this->cookie_name, $cookie, $expires, $this->cookie_path );
	}

	/**
	 * @return string
	 */
	public function get_log_type() {
		return $this->options['type'];
	}

	/**
	 * @return string
	 */
	public function get_log_file_path() {
		return $this->options['file_dir'] . "/" . $this->options['file_name'];
	}


	/**
	 * @return bool
	 */
	public function is_logging() {
		return $this->is_configured;
	}

	/**
	 * @return bool
	 */
	public function is_logging_to_file() {
		return $this->is_configured && $this->options['type'] == 'file';
	}

	/**
	 * @return array
	 */
	public function get_options() {
		return $this->options;
	}

	/**
	 * Cookie data is stored as:
	 *     option<>value|option2<>value2
	 *
	 * @param $cookie
	 *
	 * @return array
	 */
	private function parse_cookie( $cookie ) {
		$parts   = explode( "|", $cookie );
		$options = array();
		foreach ( $parts as $part ) {
			$opt                = explode( "<>", $part );
			$options[ $opt[0] ] = $opt[1];
		}

		return $options;
	}

	/**
	 *
	 * Cookie data is stored as:
	 *     option<>value|option2<>value2
	 *
	 * @param $options
	 *
	 * @return string
	 */
	private function encode_cookie( $options ) {
		$parts = array();
		foreach ( $options as $okey => $oval ) {
			$parts[] = $okey . "<>" . $oval;
		}

		return implode( "|", $parts );
	}

	public function wp_action_output_console() {
		?>
        <script>
            (function () {
                // GIZMO LOGGING
                console.log("%cPLUGIN_NAME Logging is Enabled.", "color:green;");
				<?php
				if(isset( $this->log )) {
				foreach ($this->log as $idx=>$log) :
				$title = preg_replace( '/\s+/', '', substr( $log['args'][0], 0, 80 ) );
				?>
                console.groupCollapsed(`<?="#" . $idx?>: <?=$title ?>...`);
				<?php foreach ($log['args'] as $arg):?>
                console.log(`<?=$arg?>`);
				<?php endforeach;?>
                console.groupCollapsed(`backtrace`);
                console.log(`<?=$log['backtrace']?>`);
                console.groupEnd();
                console.groupEnd();
				<?php
				endforeach;
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
		$txt = "";
		if ( isset( $this->log ) ) {
			foreach ( $this->log as $log ) {
				$txt .= PHP_EOL . $this->logfile_separator . PHP_EOL;
				$txt .= "TIME: " . date( "F j, Y, g:i a" );
				$txt .= PHP_EOL;

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
}