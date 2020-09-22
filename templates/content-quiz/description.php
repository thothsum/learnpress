<?php
/**
 * Template for displaying description of quiz.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/content-quiz/description.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

if ( ! isset( $quiz ) ) {
    return;
}

if ( ! $content = $quiz->get_content() ) {
	return;
}
?>

<div class="content-item-description quiz-description"><?php echo $content; ?></div>
