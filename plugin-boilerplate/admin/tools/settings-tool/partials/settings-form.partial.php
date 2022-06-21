<?php

/**
 * Settings Form Partial Template
 */

use \Carbon_Fields\Field\Field;
use \Carbon_Fields\Container;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/** @var $TOOL_INFO array Has tool `title`, `description`, `url`, 'slug`, `uri_slug` */
/** @var $form_html string */
?>
<h2><?= $TOOL_INFO['title'] ?></h2>
<?php
?>
<div>
	<?= $form_html ?>
</div>