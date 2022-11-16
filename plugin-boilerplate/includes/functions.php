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

		if ( ! PLUGIN_FUNC_PREFIX_has_permissions( 'logger' ) ) {
			return;
		}
		if ( method_exists( $PLUGIN_FUNC_PREFIX_logger, "log" ) ) {
			$PLUGIN_FUNC_PREFIX_logger->log( func_get_args() );
		}
	}
}

if ( ! function_exists( "PLUGIN_FUNC_PREFIX_enqueue_asset" ) ) {
	/**
	 * Define the global enqueue function.
	 *
	 *
	 * Each $option should include:
	 *  - `handle` string - WP id for the asset
	 *  - `src` string - the source for the asset
	 *  - `context` string - when to load the asset ("admin", "toolname" for just loading a tool)
	 *  - `type` string - optional - ("css", "js", default is "auto")
	 *  - `deps` array - optional - WP asset dependencies
	 *  - `ver` string - optional - version of asset
	 *  - `in_footer` boolean - optional - whether to enqueue in footer (default is false)
	 *  - `media` string - optional - css media type (default to 'all')
	 */
	function PLUGIN_FUNC_PREFIX_enqueue_asset( $options ) {
		global $PLUGIN_FUNC_PREFIX_asset_queue;

		$defaults = [
			'handle'    => "",
			'src'       => "",
			'context'   => "admin",
			'type'      => "auto",
			'deps'      => [],
			'ver'       => "",
			'in_footer' => false,
			'media'     => 'all'
		];

		$options = array_merge( $defaults, $options );

		$type = $options['type'];
		if ( $type == "auto" ) {
			// Parse the source for either js or css
			if ( stripos( $options['src'], ".js" ) != false ) {
				$type = "js";
			} else {
				$type = "css";
			}
		}
		$options['type'] = $type;

		$PLUGIN_FUNC_PREFIX_asset_queue[] = $options;
	}

	/**
	 * Enqueue scripts from the global asset queue
	 * @return void
	 */
	function PLUGIN_FUNC_PREFIX_action_wp_enqueue_assets() {
		global $PLUGIN_FUNC_PREFIX_asset_queue;

		$isAdmin = is_admin();

		$activeTool = "";
		if ( isset( $_GET['page'] ) ) {
			$activeTool = $_GET['page'];
		}

		foreach ( $PLUGIN_FUNC_PREFIX_asset_queue as $item ) {
			$shouldEnqueue = false;
			if ( $item['context'] == "admin" && $isAdmin ) {
				$shouldEnqueue = true;
			} else if ( $item['context'] == $activeTool ) {
				$shouldEnqueue = true;
			}

			if ( $shouldEnqueue ) {
				if ( $item['type'] == "js" ) {
					wp_enqueue_script( $item['handle'], $item['src'], $item['deps'], $item['ver'], $item['in_footer'] );
				} else {
					wp_enqueue_style( $item['handle'], $item['src'], $item['deps'], $item['ver'], $item['media'] );
				}
			}
		}
	}

	add_action( 'wp_enqueue_scripts', 'PLUGIN_FUNC_PREFIX_action_wp_enqueue_assets' );
	add_action( 'admin_enqueue_scripts', 'PLUGIN_FUNC_PREFIX_action_wp_enqueue_assets' );
}
