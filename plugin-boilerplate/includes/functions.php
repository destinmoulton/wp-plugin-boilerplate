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


if ( ! function_exists( "PLUGIN_FUNC_PREFIX_has_permissions" ) ) {
	/**
	 * Define the global logging function.
	 * @return void
	 */
	function PLUGIN_FUNC_PREFIX_has_permissions( $feature ) {
		if ( isset( \PLUGIN_PACKAGE\PLUGIN_CONST_PREFIX_FEATURE_PERMISSIONS[ $feature ] ) && current_user_can( \PLUGIN_PACKAGE\PLUGIN_CONST_PREFIX_FEATURE_PERMISSIONS[ $feature ] ) ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( "PLUGIN_FUNC_PREFIX_log" ) ) {
	/**
	 * Define the global logging function.
	 *
	 * The first parameter can be "warn", "error", or "info".
	 * "log" is the default.
	 *
	 * @return void
	 */
	function PLUGIN_FUNC_PREFIX_log() {
		global $PLUGIN_FUNC_PREFIX_logger;

		if ( ! PLUGIN_FUNC_PREFIX_has_permissions( 'logging' ) ) {
			return;
		}
		if ( method_exists( $PLUGIN_FUNC_PREFIX_logger, "log" ) ) {
			$PLUGIN_FUNC_PREFIX_logger->log( func_get_args() );
		}
	}
}
