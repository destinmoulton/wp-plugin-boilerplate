<?php /** @noinspection SpellCheckingInspection */

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
	protected $base_url;
	/** @var string */
	protected $description;
	/** @var array */
	protected $partials;

	public function __construct() {
		// Initialize the uri_slug using the plugin slug
		$this->uri_slug = PLUGIN_CONST_PREFIX_SLUG . "-" . $this->slug;
		$this->base_url = admin_url( 'admin.php?' . http_build_query( [ 'page' => $this->uri_slug ] ) );
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

			// Make tool information available to every
			// template partial
			$TOOL_INFO = [
				'title'       => $this->title,
				'description' => $this->description,
				'slug'        => $this->slug,
				'uri_slug'    => $this->uri_slug,
				'base_url'    => $this->base_url,
			];
			require $part['partial'];
		}
	}

	/**
	 * @param $url string The URL to redirect to.
	 * @param $js_redirect bool Should do a js redirect?
	 *
	 * @return void
	 */
	protected function redirect( $url, $js_redirect = false ) {
		if ( $js_redirect ) {
			// Clear any other partials
			$this->partials = [];

			$pvars = [
				'redirect_url' => $url
			];
			$this->add_partial( PLUGIN_CONST_PREFIX_PLUGIN_ROOT . "admin/partials/js-redirect.partial.php", $pvars );

			return;
		}
		wp_redirect( $url );
		exit();
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


	/**
	 * Load ValidFormBuilder Library
	 *
	 * Useful for making forms in tools.
	 * @return void
	 */
	protected function load_validformbuilder() {
		wp_enqueue_script( "PLUGIN_FUNC_PREFIX-validform-js", PLUGIN_CONST_PREFIX_PLUGIN_URL_ROOT . "lib/validformbuilder/js/validform.js", [], "1" );
		wp_enqueue_style( "PLUGIN_FUNC_PREFIX-validform-css", PLUGIN_CONST_PREFIX_PLUGIN_URL_ROOT . "lib/validformbuilder/css/validform.css", [], "1" );

		$path = PLUGIN_CONST_PREFIX_PLUGIN_ROOT . "lib/validformbuilder/classes/ValidFormBuilder";
		require_once( $path . "/ClassDynamic.php" );
		require_once( $path . "/Base.php" );
		require_once( $path . "/Area.php" );
		require_once( $path . "/Element.php" );
		$files = scandir( $path );

		foreach ( $files as $file ) {
			if ( ! in_array( $file, [ '.', '..' ] ) ) {
				require_once( $path . "/" . $file );
			}
		}
	}
}