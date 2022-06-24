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
	private $default_user_options = [
		"enabled" => 0,

		// 'console', 'file'
		"log_to"  => [
			"console" // js console
		],
		"php"     => [
			"log_php_errors"  => 1,
			"error_reporting" => [
				"E_ERROR"
			]
		],
		'file'    => [
			// type can be either 'log' or 'html'
			"type" => "html",
			// the file extension is determined via the file_type
			"name" => "PLUGIN_FUNC_PREFIX",
			"dir"  => WP_CONTENT_DIR,
		]
	];


	/** @var string */
	public $logfile_separator; // public so that the parser can explode it

	/** @var string[] */
	private $php_exceptions = [
		E_ERROR             => "E_ERROR",
		E_WARNING           => "E_WARNING",
		E_PARSE             => "E_PARSE",
		E_NOTICE            => "E_NOTICE",
		E_CORE_ERROR        => "E_CORE_ERROR",
		E_CORE_WARNING      => "E_CORE_WARNING",
		E_COMPILE_ERROR     => "E_COMPILE_ERROR",
		E_COMPILE_WARNING   => "E_COMPILE_WARNING",
		E_USER_ERROR        => "E_USER_ERROR",
		E_USER_WARNING      => "E_USER_WARNING",
		E_USER_NOTICE       => "E_USER_NOTICE",
		E_STRICT            => "E_STRICT",
		E_RECOVERABLE_ERROR => "E_RECOVERABLE_ERROR",
		E_DEPRECATED        => "E_DEPRECATED",
		E_USER_DEPRECATED   => "E_USER_DEPRECATED",
	];


	/** @var int[] */
	private $user_allowed_php_errors = [];

	private $log_colors = [
		'default' => 'dimgray',
		'error'   => 'maroon',
		'success' => 'forestgreen',
		'info'    => 'dodgerblue',
		'warn'    => 'darkorange'
	];

	function __construct() {

		$this->logfile_separator = "----------------------------------------------";

		if ( ! PLUGIN_FUNC_PREFIX_has_permissions( 'logger' ) ) {
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
				ini_set( "display_errors", 1 );
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
		$msg            = "";

		// Is this error type enabled by the user?
		if ( ! in_array( $errno, $this->user_allowed_php_errors ) ) {
			return;
		}

		switch ( $errno ) {
			case E_USER_ERROR:
				echo "<h2>PLUGIN_NAME Error Handler</h2>";
				echo "[$errno] $errstr<br />\n";
				echo "  Fatal error on line $errline in file $errfile";
				echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
				echo "<em>This error is appearing because you have enabled PLUGIN_NAME Logging.</em>";
				exit( 1 );

			case E_USER_WARNING:
				$data['type'] = 'warn';
				$msg          = "PHP Warning\n\n[$errno] $errstr\n\n";
				break;

			case E_USER_NOTICE:
				$data['type'] = 'info';
				$msg          = "PHP Notice\n\n[$errno] $errstr\n\n";
				break;
			case E_USER_DEPRECATED:
				$data['type'] = 'log';
				$msg          = "PHP Deprecated\n\n[$errno] $errstr\n\n";
				break;
			default:
				$data['type'] = 'log';
				$msg          = "PHP \n\n[$errno] $errstr\n\n";
				break;
		}
		$data['args'][]      = $msg;
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

		$logargs   = func_get_args();
		$backtrace = debug_backtrace();

		// Remove the mention of this function from the backtrace
		$orig = array_shift( $backtrace );

		$data              = [];
		$data['backtrace'] = print_r( $backtrace, true );
		$data['args']      = [];
		$data['type']      = 'log';
		$data['source']    = 'user';


		if ( in_array( $logargs[0], [ 'error', 'warn', 'info', 'log' ] ) ) {
			$data['type'] = $logargs[0];
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

				if ( $this->options['php']['log_php_errors'] == 1 ) {
					// Convert the allowed PHP errors into a local array
					foreach ( $this->options['php']['error_reporting'] as $excstr ) {
						$this->user_allowed_php_errors[] = constant( $excstr );
					}
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

	public function set_options( $newoptions ) {
		if ( ! isset( $newoptions['enabled'] ) ) {
			$newoptions['enabled'] = $this->options['enabled'];
		}
		if ( $this->are_options_valid( $newoptions ) ) {
			$this->options = $newoptions;
			$this->_set_user_meta();

			return true;
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
				// No user meta found/hydrated
				$this->options = $this->default_user_options;
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
		$this->options     = $this->default_user_options;
		$this->_set_user_meta();
	}

	private function _get_user_meta() {
		$meta = get_user_meta( get_current_user_id(), $this->user_meta_key );
		if ( is_array( $meta ) ) {
			return $meta[0];
		}

		return [];
	}

	private function _set_user_meta() {
		$meta = $this->_get_user_meta();
		if ( is_array( $meta ) && isset( $meta['enabled'] ) ) {
			update_user_meta( get_current_user_id(), $this->user_meta_key, $this->options );
		} else {
			add_user_meta( get_current_user_id(), $this->user_meta_key, $this->options, true );
		}
	}

	private function _delete_user_meta() {
		delete_user_meta( get_current_user_id(), $this->user_meta_key );
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
		return $this->options['file']['dir'] . "/" . $this->options['file']['name'] . "." . $this->options['file']['type'];
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


	public function wp_action_output_console() {
		?>

        <script>
            (function () {
                // GIZMO LOGGING
                console.log("%cPLUGIN_NAME Logging is Enabled.", "color:green;");
				<?php
				if(is_array( $this->log_entries )) {
				foreach ($this->log_entries as $idx=>$log) {
				// Make the title of the group a bit prettier
				$title = preg_replace( '/\v+/', '', substr( $log['args'][0], 0, 80 ) );
				$style = "color:{$this->log_colors['default']}";
				if ( isset( $colors[ $log['type'] ] ) ) {
					$style = "color:" . $this->log_colors[ $log['type'] ];
				}
				?>
                console.groupCollapsed(`%c<?=$log['source'] . " " . $log['type'] . "#" . $idx?>: <?=$title ?>...`, '<?=$style?>');

				<?php foreach ($log['args'] as $arg):?>
                console['<?=$log['type']?>'](`<?=$arg?>`);
				<?php endforeach;?>

				<?php if(isset( $data['backtrace'] )):?>
                console.groupCollapsed(`backtrace`);
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
		$txt = "";
		if ( isset( $this->log_entries ) ) {
			foreach ( $this->log_entries as $log ) {
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

	/**
	 * @return string[]
	 */
	public function get_php_exceptions() {
		return $this->php_exceptions;
	}

	/**
	 * @return string
	 */
	public function get_php_exception_name( $exc ) {
		return $this->php_exceptions[ $exc ];
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
		foreach ( $this->default_user_options as $dskey => $dsopt ) {
			if ( ! isset( $test[ $dskey ] ) ) {
				return false;
			}
		}

		return true;
	}

	public function get_default_options() {
		return $this->default_user_options;
	}

	public function clear_log_file() {
		// Clear the log
		file_put_contents( $this->get_log_file_path(), "" );
	}
}