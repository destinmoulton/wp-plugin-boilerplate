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
    <div>
        <h1 class="tool-title">PLUGIN_NAME &gt; <?= $TOOL_INFO['title'] ?></h1>
        <div>
			<?= \PLUGIN_PACKAGE\Notices::display_all() ?>
        </div>
    </div>
    <div>
		<?php if ( count( $tabs ) > 0 ): ?>
            <ul class="tab-bar">
				<?php foreach ( $tabs as $tab ): ?>
					<?php
					$aria   = '';
					$active = '';
					if ( $tab['slug'] == $TOOL_INFO['active_tab_slug'] ) {
						$aria   = 'aria-current="page"';
						$active = 'active';
					}
					?>
                    <li class="tab <?= $active ?>">
                        <a <?= $aria ?>
                                href="<?= $TOOL_INFO['base_url'] . "&tab=" . $tab['slug'] ?>"><?= $tab['title'] ?></a>
                    </li>
                    <li class="tab-gutter"></li>
				<?php endforeach; ?>
            </ul>
		<?php endif; ?>
        <div class="content">
