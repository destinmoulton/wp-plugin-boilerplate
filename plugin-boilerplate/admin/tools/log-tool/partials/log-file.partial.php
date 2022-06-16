<?php
/**
 * Output the logfile in a list format.
 */


/** @var $logfile_path string */
/** @var $logfile_entries array */
?>
<h3><?= __( "Log File Contents", PLUGIN_CONST_PREFIX_TEXTDOMAIN ); ?></h3>
<p>
    <em><?= $logfile_path ?></em>
</p>
<ul class="">
	<?
	foreach ( $logfile_entries as $entry ) {
		echo "<li>";
		echo str_replace( PHP_EOL, "<br/>", $entry );
		echo "</li>";
	}
	?>
</ul>
