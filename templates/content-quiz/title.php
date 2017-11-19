<?php
/**
 * Template for displaying title of quiz.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/content-quiz/title.php.
 *
 * @author  ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();
?>

<?php $quiz = LP_Global::course_item_quiz(); ?>

<?php if ( ! $title = $quiz->get_heading_title() ) {
	return;
} ?>

<h3 class="course-item-title quiz-title"><?php echo $title; ?></h3>
