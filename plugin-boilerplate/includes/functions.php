<?php
/**
 * PLUGIN_NAME Functions
 *
 * @package   PLUGIN_PACKAGE
 * @author    PLUGIN_AUTHOR_NAME <PLUGIN_AUTHOR_EMAIL>
 * @copyright COPYRIGHT
 * @license   PLUGIN_LICENSE_NAME
 * @link      PLUGIN_URI
 */


/**
 * Global logger object.
 *
 * @var Logger
 */
global $PLUGIN_FUNC_PREFIX_logger;
if ( class_exists( '\PLUGIN_PACKAGE\Logger' ) ) {
	$PLUGIN_FUNC_PREFIX_logger = new \PLUGIN_PACKAGE\Logger();
}

if ( ! function_exists( "PLUGIN_FUNC_PREFIX_log" ) ) {
	/**
	 * Define the global logging function.
	 * @return void
	 */
	function PLUGIN_FUNC_PREFIX_log() {
		global $PLUGIN_FUNC_PREFIX_logger;
		if ( method_exists( $PLUGIN_FUNC_PREFIX_logger, "log" ) ) {
			$PLUGIN_FUNC_PREFIX_logger->log( func_get_args() );
		}
	}
}
