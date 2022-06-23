<?php

/**
 * Options for Log Tool.
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

use PLUGIN_PACKAGE\Admin;

/** @var $TOOL_INFO array Has tool `title`, `description`, `url`, 'slug`, `uri_slug` */
/** @var $logger_is_running bool */
/** @var $is_logging_to_file bool */
/** @var $log_file_path bool */
/** @var $form_html string */
?>

<h1><?= $TOOL_INFO['title'] ?></h1>
<div>
    <h3>Status: <?= $logger_is_running ? "Logging Enabled" : "Logging Disabled" ?></h3>
    <fieldset style="border:1px solid gray; padding: .4rem;">
        <legend>Options</legend>
        <ul>
            <li>
                <a href="<?= Admin\PLUGIN_FUNC_PREFIX_tool_url( $TOOL_INFO, [ 'action' => 'reset_logging' ] ) ?>"><?= __( "Reset Logging", PLUGIN_CONST_PREFIX_TEXTDOMAIN ) ?></a>
            </li>
			<?php if ( $logger_is_running ): ?>
                <li>
                    <a href="<?= Admin\PLUGIN_FUNC_PREFIX_tool_url( $TOOL_INFO, [ 'action' => 'disable_logging' ] ) ?>"><?= __( "Turn Off Logging", PLUGIN_CONST_PREFIX_TEXTDOMAIN ) ?></a>
                </li>
                <li>
                    <a href="<?= Admin\PLUGIN_FUNC_PREFIX_tool_url( $TOOL_INFO, [ 'action' => 'test_logging' ] ) ?>"><?= __( "Invoke a Test Log Message", PLUGIN_CONST_PREFIX_TEXTDOMAIN ) ?></a>
                </li>
				<?php if ( $is_logging_to_file ): ?>
                    <li>
                        <a href="<?= Admin\PLUGIN_FUNC_PREFIX_tool_url( $TOOL_INFO, [ 'action' => 'clear_file_log' ] ) ?>"><?= __( "Clear The File Log", PLUGIN_CONST_PREFIX_TEXTDOMAIN ) ?></a>
                    </li>
				<?php endif; ?>
			<?php else: ?>
                <li>
                    <a href="<?= Admin\PLUGIN_FUNC_PREFIX_tool_url( $TOOL_INFO, [ 'action' => 'enable_logging' ] ) ?>"><?= __( "Enable Logging", PLUGIN_CONST_PREFIX_TEXTDOMAIN ) ?></a>
                </li>
			<?php endif; ?>
        </ul>
    </fieldset>

	<?php if ( $logger_is_running ): ?>
        <div>
			<?= $form_html ?>
        </div>
	<?php endif; ?>
</div>