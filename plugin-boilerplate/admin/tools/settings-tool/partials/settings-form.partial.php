<?php

/**
 * Settings Form Partial Template
 */

use \Carbon_Fields\Field\Field;
use \Carbon_Fields\Container;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/** @var $TOOL_INFO array Has tool `title`, `description`, `url`, 'slug`, `uri_slug` */
/** @var $fields array The fields (from the constant) */
/** @var $settings array The current settings (or default) */
?>
<h2><?= $TOOL_INFO['title'] ?></h2>
<?php
?>
<div>
	<?php foreach ( $fields as $set ) {

		switch ( $set['type'] ) {
			case 'text':

				print_r( $field );
				break;
			default:
				break;
		}
//		switch ( $set['type'] ) {
//			case 'text':
//				$form_builder->input( $set );
//				break;
//			case 'select':
//                $field['string']= $atts;
//					'type'        => 'select',
//					'name'        => $set['name'],
//					'label'       => $set['label'],
//					'id'          => $set['name'],
//					'value'       => $settings[ $set['name'] ],
//					'class'       => $class,
//					'placeholder' => $placeholder,
//					'string'      => ""
//				];
//				$form_builder->input($field);
//				break;
//		} // switch
	} // foreach ?>
</div>