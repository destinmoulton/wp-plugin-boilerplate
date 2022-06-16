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

	public function __construct() {
		// Initialize the uri_slug using the plugin slug
		$this->uri_slug = PLUGIN_CONST_PREFIX_SLUG . "-" . $this->slug;
		$this->init();
	}

	/**
	 * @return void
	 */
	public function init() {
	}

	/**
	 * @return string
	 */
	public function getToolTitle() {
		return $this->title;
	}

	/**
	 * @return string
	 */
	public function getToolSlug() {
		return $this->slug;
	}

	/**
	 * @return string
	 */
	public function getToolURISlug() {
		return $this->uri_slug;
	}

	/**
	 * @return string
	 */
	public function getToolDescription() {
		return $this->description;
	}

	/**
	 * @return array ie array($this, "render");
	 */
	public function getToolRenderMethod() {
		return array( $this, "render" );
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