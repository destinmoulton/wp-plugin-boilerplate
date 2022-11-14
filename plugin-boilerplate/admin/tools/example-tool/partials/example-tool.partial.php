<?php

/**
 * Example partial template.
 *
 * Variables are passed in using a keyed array to
 * the add_partial() function call in the tool class.
 *
 * $TOOL_INFO is passed to every partial template.
 */

use PLUGIN_PACKAGE\Admin;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/** @var $TOOL_INFO array Has tool `title`, `description`, `url`, 'slug`, `uri_slug` */
/** @var $hello_world string */
?>
<h2><?= $TOOL_INFO['title'] ?></h2>
<p><?= $hello_world ?></p>
<p>
    <a href="<?= Admin\PLUGIN_FUNC_PREFIX_tool_url( $TOOL_INFO, [ 'action' => 'do_something' ] ) ?>"><?= __( "Example Action", PLUGIN_CONST_PREFIX_TEXTDOMAIN ) ?></a>
</p>
