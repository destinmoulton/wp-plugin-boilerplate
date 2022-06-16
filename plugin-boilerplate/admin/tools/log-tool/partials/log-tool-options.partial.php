<?php

/**
 * Options for debug log.
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/** @var $tool_url string */
/** @var $logger_is_running bool */
/** @var $is_logging_to_file bool */
/** @var $log_file_path bool */
?>

<h1>Error Log</h1>
<div>
    <h3>Status: <?= $logger_is_running ? "Logging Enabled" : "Logging Disabled" ?></h3>
    <fieldset style="border:1px solid gray; padding: .4rem;">
        <legend>Options</legend>
        <ul>

			<?php if ( $logger_is_running ): ?>
                <li>
                    <a href="<?= admin_url( $tool_url . '&action=disable_logging' ) ?>"><?= __( "Turn Off Logging", PLUGIN_CONST_PREFIX_TEXTDOMAIN ) ?></a>
                </li>
                <li>
                    <a href="<?= admin_url( $tool_url . '&action=test_logging' ) ?>"><?= __( "Invoke a Test Log Message", PLUGIN_CONST_PREFIX_TEXTDOMAIN ) ?></a>
                </li>
				<?php if ( $is_logging_to_file ): ?>
                    <li>
                        <a href="<?= admin_url( $tool_url . '&action=clear_file_log' ) ?>"><?= __( "Invoke a Test Log Message", PLUGIN_CONST_PREFIX_TEXTDOMAIN ) ?></a>
                    </li>
                    <li>
                        <a href="<?= admin_url( $tool_url . '&action=log_to_console' ) ?>"><?= __( "Switch to Console Logging", PLUGIN_CONST_PREFIX_TEXTDOMAIN ) ?></a>
                    </li>
				<?php else: ?>
                    <li>
                        <a href="<?= admin_url( $tool_url . '&action=log_to_file' ) ?>"><?= __( "Switch to File Logging", PLUGIN_CONST_PREFIX_TEXTDOMAIN ) ?></a>
                    </li>
				<?php endif; ?>
			<?php else: ?>
                <li>
                    <a href="<?= admin_url( $tool_url . '&action=enable_logging' ) ?>"><?= __( "Enable Logging", PLUGIN_CONST_PREFIX_TEXTDOMAIN ) ?></a>
                </li>
			<?php endif; ?>
        </ul>
    </fieldset>

</div>