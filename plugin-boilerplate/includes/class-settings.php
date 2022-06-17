<?php

/**
 * PLUGIN_NAME Settings Class
 *
 * One wp option is stored for all of the plugin settings
 * keyed to PLUGIN_CONST_PREFIX_SLUG-settings
 *
 * @package   PLUGIN_PACKAGE
 * @author    PLUGIN_AUTHOR_NAME <PLUGIN_AUTHOR_EMAIL>
 * @copyright COPYRIGHT
 * @license   PLUGIN_LICENSE_NAME
 * @link      PLUGIN_URI
 */

namespace PLUGIN_PACKAGE;

class Settings {
	private static $option_key = PLUGIN_CONST_PREFIX_SLUG . "-settings";

	/**
	 * Return the defaults if not set in the db.
	 *
	 * Options are stored as a single json object.
	 *
	 * @return array
	 */
	public static function get_all() {
		/**
		 * Define your default settings.
		 */
		$default_settings = array(
			'test-option-text' => "option-value"
		);

		return get_option( self::$option_key, $default_settings );
	}

	/**
	 * Get a single setting.
	 *
	 * @param $setting_key string
	 *
	 * @return mixed|null
	 */
	public static function get( $setting_key ) {
		$settings = self::get_all_settings();

		return $settings[ $setting_key ] ?? null;
	}

	/**
	 * Set a single setting.
	 *
	 * @param $setting_key string
	 * @param $setting_value mixed
	 *
	 * @return bool
	 */
	public static function set( $setting_key, $setting_value ) {
		$settings = self::get_all();

		$settings[ $setting_key ] = $setting_value;

		return self::set_all( $settings );
	}

	/**
	 * Update all the settings.
	 *
	 * @param $new_settings mixed
	 *
	 * @return bool
	 */
	public static function set_all( $settings ) {
		return update_option( self::$option_key, $settings );
	}
}