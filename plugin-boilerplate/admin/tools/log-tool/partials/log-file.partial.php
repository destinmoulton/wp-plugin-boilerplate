<?php
/**
 * Output the logfile in a list format.
 */

use PLUGIN_PACKAGE\Admin;

/** @var $TOOL_INFO array */
/** @var $logfile_path string */
/** @var $logfile_url string */
/** @var $is_file_logging_enabled bool */
/** @var $logfile_entries array */
?>
<?php $status = $is_file_logging_enabled ? "Enabled" : "Disabled"; ?>
<div class="card">
    <h4>Details</h4>
    <ul>
        <li>Status: File Log <b><?= $status ?></b></li>
        <li>Full Path: <b><?= $logfile_path ?></b></li>
        <li>Raw Link: <b><a href="<?= $logfile_url ?>" target="_blank"><?= $logfile_url ?></a></b></li>
    </ul>
    <h4>Commands</h4>
    <ul>
        <li>
            <a href="<?= Admin\PLUGIN_FUNC_PREFIX_tool_url( $TOOL_INFO, [
				'action' => "clear_log_file",
				'tab'    => "logfile"
			] ) ?>"><?= __( "Clear The Log File", PLUGIN_CONST_PREFIX_TEXTDOMAIN ) ?></a>
        </li>
    </ul>
</div>
<h3><?= __( "Log File Contents", PLUGIN_CONST_PREFIX_TEXTDOMAIN ); ?></h3>
<ul class="PLUGIN_FUNC_PREFIX-log-tool-list">
	<?php
	if ( count( $logfile_entries ) == 1 && trim( $logfile_entries[0] ) == "" ) {
		echo "<li>" . __( "No log entries.", PLUGIN_CONST_PREFIX_TEXTDOMAIN ) . "</li>";
	}
	foreach ( $logfile_entries as $entry ) {
		$entry = trim( $entry );
		if ( $entry !== "" ) {
			echo "<li>";
			echo str_replace( PHP_EOL, "<br/>", $entry );
			echo "</li>";
		}
	}
	?>
</ul>
