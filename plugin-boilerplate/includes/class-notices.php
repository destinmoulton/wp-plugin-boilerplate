<?php


/**
 * PLUGIN_NAME Notices Class
 *
 * Wrapper for wp notices fired during the
 * admin_notices hook.
 *
 * @package   PLUGIN_PACKAGE
 * @author    PLUGIN_AUTHOR_NAME <PLUGIN_AUTHOR_EMAIL>
 * @copyright COPYRIGHT
 * @license   PLUGIN_LICENSE_NAME
 * @link      PLUGIN_URI
 */

namespace PLUGIN_PACKAGE;
global $PLUGIN_FUNC_PREFIX_notices;
$PLUGIN_FUNC_PREFIX_notices = [];

class Notices {


	public static function set_hook() {
		add_action( 'admin_notices', '\PLUGIN_PACKAGE\Notices::wp_action_admin_notices' );
	}

	/**
	 * @param $msg string
	 *
	 * @return void
	 */
	public static function success( $msg ) {
		self::add( $msg, "success" );
	}

	/**
	 * @param $msg string
	 *
	 * @return void
	 */
	public static function error( $msg ) {
		self::add( $msg, "error" );
	}

	/**
	 * @param $msg string
	 *
	 * @return void
	 */
	public static function warning( $msg ) {
		self::add( $msg, "warning" );
	}

	/**
	 * @param $msg string
	 *
	 * @return void
	 */
	public static function info( $msg ) {
		self::add( $msg, "info" );
	}

	/**
	 * @param $msg string Notice to display.
	 * @param $type string One of 'error', 'warning', 'success', 'info'
	 *
	 * @return void
	 */
	public static function add( $msg, $type ) {
		global $PLUGIN_FUNC_PREFIX_notices;
		$PLUGIN_FUNC_PREFIX_notices[] = [
			'msg'  => $msg,
			'type' => $type
		];
	}


	/**
	 * Called by the admin-header.partial.php
	 *
	 * @return void
	 */
	public static function display_all() {
		global $PLUGIN_FUNC_PREFIX_notices;
		if ( count( $PLUGIN_FUNC_PREFIX_notices ) > 0 ) {
			foreach ( $PLUGIN_FUNC_PREFIX_notices as $note ) {
				?>
                <div class="notice notice-<?= $note['type'] ?> is-dismissible">
                    <p><?php _e( $note['msg'], PLUGIN_CONST_PREFIX_TEXTDOMAIN ); ?></p>
                </div>
				<?php
			}
		}
	}


}
