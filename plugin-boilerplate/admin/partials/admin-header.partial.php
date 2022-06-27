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
?>
<div class="PLUGIN_FUNC_PREFIX-tool-container">
    <div class="container">
        <h1 class="tool-title">PLUGIN_NAME <?= $TOOL_INFO['title'] ?></h1>
        <div>
			<?= \PLUGIN_PACKAGE\Notices::display_all() ?>
        </div>
    </div>
    <div class="container">
		<?php if ( count( $tabs ) > 0 ): ?>
            <ul class="nav nav-tabs">
				<?php foreach ( $tabs as $tab ): ?>
					<?php
					$aria   = '';
					$active = '';
					if ( $tab['slug'] == $TOOL_INFO['active_tab_slug'] ) {
						$aria   = 'aria-current="page"';
						$active = 'active';
					}
					?>
                    <li class="nav-item">
                        <a class="nav-link <?= $active ?>"
							<?= $aria ?>
                           href="<?= $TOOL_INFO['base_url'] . "&tab=" . $tab['slug'] ?>"><?= $tab['title'] ?></a>
                    </li>
				<?php endforeach; ?>
            </ul>
		<?php endif; ?>
