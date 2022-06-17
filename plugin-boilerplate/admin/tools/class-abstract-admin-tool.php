<?php

namespace PLUGIN_PACKAGE\Admin\Tools;

/**
 * This is the base class for the Admin Tools.
 */
abstract class AbstractAdminTool {
	/** @var string */
	protected $title;
	/** @var string */
	protected $slug;
	/** @var string */
	protected $uri_slug;
	/** @var string */
	protected $description;
	/** @var array */
	protected $partials;

	public function __construct() {
		// Initialize the uri_slug using the plugin slug
		$this->uri_slug = PLUGIN_CONST_PREFIX_SLUG . "-" . $this->slug;
		$this->init();
	}

	/**
	 * @return void
	 */
	protected function init() {
	}

	/**
	 * Load the header partial and start the render process.
	 *
	 * @return void
	 */
	public function run() {
		// Add the admin header partial first
		$this->add_partial( PLUGIN_CONST_PREFIX_PLUGIN_ROOT . "admin/partials/admin-header.partial.php" );
		$this->render();
		$this->display_partials();
	}

	/**
	 * @return string
	 */
	public function get_title() {
		return $this->title;
	}

	/**
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * @return string
	 */
	public function get_uri_slug() {
		return $this->uri_slug;
	}

	/**
	 * @return string
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * @return array ie array($this, "render");
	 */
	public function get_run_method() {
		return array( $this, "run" );
	}

	/**
	 * Extract the partial variables for use in
	 * the required partial.
	 *
	 * @return void
	 */
	protected function display_partials() {
		foreach ( $this->partials as $part ) {
			// Extract the keyed array into
			// the variable name(s) for use
			// in the required partial template
			extract( $part['vars'] );
			require $part['partial'];
		}
	}

	/**
	 * @param $partial string
	 * @param $vars array The variables for the partial.
	 *
	 * @return void
	 */
	protected function add_partial( $partial, $vars = array() ) {

		if ( ! is_string( $partial ) ) {
			return;
		}

		$data             = [
			'partial' => $partial,
			'vars'    => $vars,
		];
		$this->partials[] = $data;
	}

	/**
	 * @return string
	 */
	public function get_path() {
		return \PLUGIN_CONST_PREFIX_PLUGIN_ROOT . "/admin/tools/" . $this->slug . "/";
	}

	/**
	 * @return void
	 */
	abstract public function render();
}