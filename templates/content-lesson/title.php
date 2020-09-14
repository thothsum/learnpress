<?php
/**
 * Template for displaying title of lesson.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/content-lesson/title.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  3.1.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();
if ( ! isset( $title ) ) {
    return;
}
if ( ! isset( $get_title ) ) {
    return;
}

if ( ! $get_title ) {
	return;
}
?>

<h3 class="course-item-title question-title"><?php echo $title; ?></h3>