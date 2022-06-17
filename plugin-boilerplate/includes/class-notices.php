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

class Notices {
	private $notices = [];


	public function run() {
		add_action( 'admin_notices', array( $this, "wp_action_admin_notices" ) );
	}

	/**
	 * @param $msg string
	 *
	 * @return void
	 */
	public function success( $msg ) {
		$this->add( $msg, "success" );
	}

	/**
	 * @param $msg string
	 *
	 * @return void
	 */
	public function error( $msg ) {
		$this->add( $msg, "error" );
	}

	/**
	 * @param $msg string
	 *
	 * @return void
	 */
	public function warning( $msg ) {
		$this->add( $msg, "warning" );
	}

	/**
	 * @param $msg string
	 *
	 * @return void
	 */
	public function info( $msg ) {
		$this->add( $msg, "info" );
	}

	/**
	 * @param $msg string Notice to display.
	 * @param $type string One of 'error', 'warning', 'success', 'info'
	 *
	 * @return void
	 */
	public function add( $msg, $type ) {
		$this->notices[] = [
			'msg'  => $msg,
			'type' => $type
		];
	}


	/**
	 * Called by the 'admin_notices' hook set in the run()
	 * function
	 *
	 * @return void
	 */
	public function wp_action_admin_notices() {
		if ( count( $this->notices ) > 0 ) {
			foreach ( $this->notices as $note ) {
				?>
				<div class="notice notice-<?= $note['type'] ?> is-dismissible">
					<p><?php _e( $note['msg'], PLUGIN_CONST_PREFIX_TEXTDOMAIN ); ?></p>
				</div>
				<?php
			}
		}
	}


}
