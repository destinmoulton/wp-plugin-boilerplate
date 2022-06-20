<?php

/**
 * Settings Form Partial Template
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/** @var $TOOL_INFO array Has tool `title`, `description`, `url`, 'slug`, `uri_slug` */
/** @var $form_builder \Formr\Formr The form builder lib */
/** @var $fields array The fields (from the constant) */
/** @var $settings array The current settings (or default) */
?>
<h2><?= $TOOL_INFO['title'] ?></h2>
<div>
	<?= $form_builder->form_open() ?>
	<?php foreach ( $fields as $set ) {
		$placeholder = $set['placeholder'] ?? "";
		$class       = $set['class'] ?? "";
		switch ( $set['type'] ) {
			case 'text':
				$field = [
					'name'        => $set['name'],
					'label'       => $set['label'],
					'id'          => $set['name'],
					'value'       => $settings[ $set['name'] ],
					'class'       => $class,
					'placeholder' => $placeholder,
					'string'      => ""
				];
				$form_builder->text( $field );
				break;
		} // switch
	} // foreach ?>
	<?= $form_builder->submit_button() ?>
	<?= $form_builder->form_close() ?>
</div>