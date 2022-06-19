<?php
/**
 * The PLUGIN_NAME Admin Tools Functions
 *
 * @package   PLUGIN_PACKAGE
 * @author    PLUGIN_AUTHOR_NAME <PLUGIN_AUTHOR_EMAIL>
 * @copyright COPYRIGHT
 * @license   PLUGIN_LICENSE_NAME
 * @link      PLUGIN_URI
 */

namespace PLUGIN_PACKAGE\Admin\Tools;
/**
 * Build a tool url
 *
 * @param $TOOL_INFO array
 * @param $query_args array
 *
 * @return string
 */
function PLUGIN_FUNC_PREFIX_tool_url( $TOOL_INFO, $query_args ) {
	$uri_parts = [];
	foreach ( $query_args as $qkey => $qval ) {
		$uri_parts[] = $qkey . "=" . $qval;
	}
	$quri = implode( "&", $uri_parts );

	return $TOOL_INFO['base_url'] . "&" . $quri;
}