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

/**
 * Global asset enqueue array
 *
 * These are used by the enqueue functions in functions.php
 *
 * Each array item should include:
 *  - `handle` string - WP id for the asset
 *  - `src` string - the source for the asset
 *  - `context` string - when to load the asset ("admin", "toolname" for just loading a tool)
 *  - `type` string - optional - ("css", "js", default is "auto")
 *  - `deps` array - optional - WP asset dependencies
 *  - `ver` string - optional - version of asset
 *  - `in_footer` boolean - optional - whether to enqueue in footer (default is false)
 *  - `media` string - optional - css media type (default to 'all')
 *
 * @var $PLUGIN_FUNC_PREFIX_asset_queue
 */
global $PLUGIN_FUNC_PREFIX_asset_queue;
$PLUGIN_FUNC_PREFIX_asset_queue = [];
