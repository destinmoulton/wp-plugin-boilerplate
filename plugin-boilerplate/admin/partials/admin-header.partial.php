<?php

/**
 * PLUGIN_NAME Admin Header Partial
 *
 * Display above tool partials.
 *
 * @package   PLUGIN_PACKAGE
 * @author    PLUGIN_AUTHOR_NAME <PLUGIN_AUTHOR_EMAIL>
 * @copyright COPYRIGHT
 * @license   PLUGIN_LICENSE_NAME
 * @link      PLUGIN_URI
 */


/** @var $TOOL_INFO array */
/** @var $tabs array */
/** @var $active_tab_slug string */
?>
<h1>PLUGIN_NAME <?= $TOOL_INFO['title'] ?></h1>
<div>
	<?= \PLUGIN_PACKAGE\Notices::display_all() ?>
</div>
<?php if ( count( $tabs ) > 0 ): ?>
    <ul class="nav nav-tabs">
		<?php foreach ( $tabs as $tab ): ?>
			<?php if ( $tab['slug'] == $active_tab_slug ): ?>
				<?php // TODO: Active tab handling?>
			<?php endif; ?>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#"><?= $tab['title'] ?></a>
            </li>
		<?php endforeach; ?>
    </ul>
<?php endif; ?>
