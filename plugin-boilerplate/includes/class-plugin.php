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
		// Settings are configured in settings.constant.php
		require_once( PLUGIN_CONST_PREFIX_PLUGIN_ROOT . "constants/settings.constant.php" );

		// The Settings class with static methods
		require_once( PLUGIN_CONST_PREFIX_PLUGIN_ROOT . "includes/class-settings.php" );

		// Logger class
		require_once( PLUGIN_CONST_PREFIX_PLUGIN_ROOT . "includes/class-logger.php" );

		// Initialize logger function PLUGIN_FUNC_PREFIX_log()
		// Even if you don't use the logger,
		// you might want to keep this permanently loaded
		// You can add logging messages in your code, and then re-add
		// the logger above when you want to start to actually log stuff.
		require_once( PLUGIN_CONST_PREFIX_PLUGIN_ROOT . "functions/logger.php" );
	}

	/**
	 * @return void
	 */
	private static function require_admin() {
		// WP Notices
		require_once( PLUGIN_CONST_PREFIX_PLUGIN_ROOT . "includes/class-notices.php" );


		// Admin class
		require_once( PLUGIN_CONST_PREFIX_PLUGIN_ROOT . "admin/class-admin.php" );

		$admin = new Admin\Admin();
		$admin->run();
	}
}