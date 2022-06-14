<?php

namespace PLUGIN_PACKAGE\Admin\Tools;

/**
 * This is the base class for the Admin Tools.
 */
abstract class AbstractAdminTool {
	protected $title;
	protected $slug;
	protected $description;

	public function __construct()
	{
		$this->init();
	}

	/**
	 * @return void
	 */
	public function init()
	{
	}

	/**
	 * @return string
	 */
	public function getToolTitle()
	{
		return $this->title;
	}

	/**
	 * @return string
	 */
	public function getToolSlug()
	{
		return $this->slug;
	}

	/**
	 * @return string
	 */
	public function getToolDescription()
	{
		return $this->description;
	}

	/**
	 * @return array ie array($this, "render");
	 */
	public function getToolRenderMethod()
	{
		return array($this, "render");
	}

	/**
	 * @return void
	 */
	abstract public function render();
}