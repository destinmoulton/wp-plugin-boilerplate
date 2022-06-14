<?php

/**
 * Options for debug log.
 */

defined('ABSPATH') or die('No script kiddies please!');
/** @var Logger $gizmo_logger */
global $gizmo_logger;
$isFileLog = $gizmo_logger->is_logging() && $gizmo_logger->get_log_type() == "file";
?>

<h1>Error Log</h1>
<div>
    <h3>Status: <?= $gizmo_logger->is_logging() ? "Logging Enabled" : "Logging Disabled" ?></h3>
    <fieldset style="border:1px solid gray; padding: .4rem;">
        <legend>Options</legend>
        <ul>

            <?php if ($gizmo_logger->is_logging()): ?>
                <li>
                    <a href="<?= admin_url('admin.php?page=taos-admin-debug&action=disable_logging') ?>"
                    >Disable Logging</a>
                </li>
                <li>
                    <a href="<?= admin_url('admin.php?page=taos-admin-debug&action=test_logging') ?>"
                    >Test the Logger</a>
                </li>
                <?php if ($isFileLog): ?>
                    <li><a href="<?= admin_url('admin.php?page=taos-admin-debug&action=clear_file_log') ?>">Clear The
                            File
                            Log</a></li>
                    <li><a href="<?= admin_url('admin.php?page=taos-admin-debug&action=log_to_console') ?>">Switch
                            to
                            Console Logging</a></li>
                <?php else: ?>
                    <li><a href="<?= admin_url('admin.php?page=taos-admin-debug&action=log_to_file') ?>">Switch to
                            File
                            Logging</a></li>
                <?php endif; ?>
            <?php else: ?>
                <li><a href="<?= admin_url('admin.php?page=taos-admin-debug&action=enable_logging') ?>">Enable
                        Logging</a></li>
            <?php endif; ?>
        </ul>
    </fieldset>

    <?php if ($gizmo_logger->is_logging() && $isFileLog): ?>
        <h3>Log File Contents</h3>
        <p>
            <em><?= $gizmo_logger->get_log_file_path() ?></em>
        </p>
        <ul class="taos-admin-debug-list">
            <?
            $debug_log = file_get_contents($gizmo_logger->get_log_file_path());
            $entries = explode($gizmo_logger->logfile_separator, $debug_log);

            foreach ($entries as $entry) {
                echo "<li>";
                echo str_replace(PHP_EOL, "<br/>", $entry);
                echo "</li>";
            }

            ?>
        </ul>
    <?php endif; ?>
</div>