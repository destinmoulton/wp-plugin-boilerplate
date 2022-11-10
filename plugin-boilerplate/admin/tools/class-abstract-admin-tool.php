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
	/** @var array */
	protected $tabs = [];
	/** @var string */
	protected $active_tab_slug = "";

	public function __construct() {
		// Initialize the uri_slug using the plugin slug
		$this->uri_slug = PLUGIN_CONST_PREFIX_SLUG . "-" . $this->slug;
		$this->base_url = admin_url( 'admin.php?' . http_build_query( [ 'page' => $this->uri_slug ] ) );

		// Init before enqueue
		$this->init();

		if ( $this->is_active() ) {
			// Enqueue only if this tool is running
			// so that other admin sections aren't
			// changed or burdened by the enqueues
			$this->enqueue();
		}
	}

	/**
	 * @return void
	 */
	protected function enqueue() {
		$enq_opts = [
			'handle'  => "PLUGIN_FUNC_PREFIX-admin-css",
			'context' => "admin",
			'src'     => PLUGIN_CONST_PREFIX_PLUGIN_URL_ROOT . "assets/admin.css",
			'version' => PLUGIN_CONST_PREFIX_VERSION
		];
		PLUGIN_FUNC_PREFIX_enqueue_asset( $enq_opts );
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
		if ( count( $this->tabs ) > 0 ) {
			$hdata = [
				'tabs'            => $this->tabs,
				'active_tab_slug' => $this->active_tab_slug
			];
		} else {
			// Make the current page into the tab
			$this->active_tab_slug = $this->uri_slug;
			$hdata                 = [
				'tabs'            => [
					[ 'slug' => $this->uri_slug, 'title' => $this->title ]
				],
				'active_tab_slug' => $this->uri_slug
			];
		}
		// header
		$this->add_partial( PLUGIN_CONST_PREFIX_PLUGIN_ROOT . "admin/partials/admin-header.partial.php", $hdata );
		if ( ! PLUGIN_FUNC_PREFIX_has_permissions( $this->slug ) ) {
			// Don't render without permissions
			\PLUGIN_PACKAGE\Notices::error( "You do not have permissions to access this." );
		} else {
			$this->render();
		}
		// footer
		$this->add_partial( PLUGIN_CONST_PREFIX_PLUGIN_ROOT . "admin/partials/admin-footer.partial.php", $hdata );
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
				'title'           => $this->title,
				'description'     => $this->description,
				'slug'            => $this->slug,
				'uri_slug'        => $this->uri_slug,
				'base_url'        => $this->base_url,
				'active_tab_slug' => $this->active_tab_slug
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
		exit;
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
	 * @param $title
	 * @param $slug
	 * @param $func array ie [$this, "tab_method"]
	 *
	 * @return void
	 */
	protected function add_tab( $slug, $title, $func ) {
		$this->tabs[] = [
			'slug'   => $slug,
			'title'  => $title,
			'method' => $func
		];
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


	protected function route_tabs() {
		if ( ! isset( $this->tabs[0] ) ) {
			return;
		}
		// Default tab is first
		$tab                   = $this->tabs[0];
		$this->active_tab_slug = $tab['slug'];
		if ( isset( $_GET['tab'] ) ) {
			$t = $this->get_tab( $_GET['tab'] );
			if ( isset( $t['slug'] ) ) {
				$tab                   = $t;
				$this->active_tab_slug = $t['slug'];
			}
		}
		call_user_func( $tab['method'] );
	}


	/**
	 * Get a tab by its slug
	 *
	 * @param $tab_slug string
	 *
	 * @return array
	 */
	protected function get_tab( $tab_slug ) {
		foreach ( $this->tabs as $tab ) {
			if ( $tab['slug'] == $tab_slug ) {
				return $tab;
			}
		}

		return [];
	}

	protected function is_active() {
		if ( ! isset( $_GET['page'] ) ) {
			return false;
		}
		if ( $_GET['page'] == $this->uri_slug ) {

			return true;
		}

		return false;
	}
}