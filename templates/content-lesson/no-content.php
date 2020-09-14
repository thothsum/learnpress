<?php
/**
 * Template for displaying no content lesson.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/content-lesson/no-content.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();
if ( ! isset( $item ) ) {
    return;
}
$message = __( 'Lesson content is empty.', 'learnpress' );

if ( $item->current_user_can_edit() ) {
	$message .= sprintf( '<a href="%s" class="edit-content">%s</a>', $item->get_edit_link(), __( 'Edit', 'learnpress' ) );
}

learn_press_display_message( $message, 'notice' );