<?php

/**
 * PLUGIN_NAME Globals
 *
 * @package   PLUGIN_PACKAGE
 * @author    PLUGIN_AUTHOR_NAME <PLUGIN_AUTHOR_EMAIL>
 * @copyright COPYRIGHT
 * @license   PLUGIN_LICENSE_NAME
 * @link      PLUGIN_URI
 */

/**
 * Global logger object.
 *
 * @var \PLUGIN_PACKAGE\Logger
 */
global $PLUGIN_FUNC_PREFIX_logger;
if ( class_exists( '\PLUGIN_PACKAGE\Logger' ) ) {
	$PLUGIN_FUNC_PREFIX_logger = new \PLUGIN_PACKAGE\Logger();
}
