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

$status = $logger_is_running ? "Enabled" : "Disabled";
?>

<div>
    <div class="card">
        <h4>Details</h4>
        <ul>
            <li>Status: Console Log <b><?= $status ?></b></li>
        </ul>
        <h4>Commands</h4>
        <ul>
			<?php if ( $logger_is_running ): ?>
                <li>
                    <a href="<?= Admin\PLUGIN_FUNC_PREFIX_tool_url( $TOOL_INFO, [ 'action' => 'disable_logging' ] ) ?>"><?= __( "Turn Off Logging", PLUGIN_CONST_PREFIX_TEXTDOMAIN ) ?></a>
                </li>
                <li>
                    <a href="<?= Admin\PLUGIN_FUNC_PREFIX_tool_url( $TOOL_INFO, [ 'action' => 'test_user_log' ] ) ?>"><?= __( "Invoke a Test Log Message", PLUGIN_CONST_PREFIX_TEXTDOMAIN ) ?></a>
                </li>
                <li>
                    <a href="<?= Admin\PLUGIN_FUNC_PREFIX_tool_url( $TOOL_INFO, [ 'action' => 'test_php_log' ] ) ?>"><?= __( "Invoke a Test of PHP User Exceptions", PLUGIN_CONST_PREFIX_TEXTDOMAIN ) ?></a>
                </li>
			<?php else: ?>
                <li>
                    <a href="<?= Admin\PLUGIN_FUNC_PREFIX_tool_url( $TOOL_INFO, [ 'action' => 'enable_logging' ] ) ?>"><?= __( "Enable Logging", PLUGIN_CONST_PREFIX_TEXTDOMAIN ) ?></a>
                </li>
			<?php endif; ?>
            <li>
                <a href="<?= Admin\PLUGIN_FUNC_PREFIX_tool_url( $TOOL_INFO, [ 'action' => 'reset_logging' ] ) ?>"><?= __( "Reset Your Log Tool Metadata", PLUGIN_CONST_PREFIX_TEXTDOMAIN ) ?></a>
            </li>
        </ul>
    </div>

	<?php if ( $logger_is_running ): ?>
        <div class="bg-white border rounded-1 p-2 mt-2">
            <div>
				<?= $form_html ?>
            </div>
        </div>
	<?php endif; ?>
</div>
