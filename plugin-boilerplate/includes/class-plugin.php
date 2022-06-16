<?php

/**
 * PLUGIN_NAME Plugin Class
 *  - Initialize and run the plugin.
 *
 * @package   PLUGIN_PACKAGE
 * @author    PLUGIN_AUTHOR_NAME <PLUGIN_AUTHOR_EMAIL>
 * @copyright COPYRIGHT
 * @license   PLUGIN_LICENSE_NAME
 * @link      PLUGIN_URI
 */

namespace PLUGIN_PACKAGE;

class Plugin {

	public static function run() {
		self::require_primary();

		// Only load the admin functionality if the user is qualified
		if ( \current_user_can( PLUGIN_CONST_PREFIX_MIN_ADMIN_CAPABILITY ) ) {
			self::require_admin();
		}
	}

	/**
	 * @return void
	 */
	private static function require_primary() {
		// Logger class
		require_once( PLUGIN_CONST_PREFIX_PLUGIN_ROOT . "includes/class-logger.php" );

		// Initialize logger function(s)
		require_once( PLUGIN_CONST_PREFIX_PLUGIN_ROOT . "functions/logger.php" );
	}

	/**
	 * @return void
	 */
	private static function require_admin() {
		// Admin class
		require_once( PLUGIN_CONST_PREFIX_PLUGIN_ROOT . "admin/class-admin.php" );

		$admin = new Admin\Admin();
		$admin->run();
	}
}