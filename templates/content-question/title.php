<?php
/**
 * Template for displaying title of question.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/content-question/title.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  3.1.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

if ( ! isset( $global_question ) ) {
    return;
}
?>

<h4 class="question-title"><?php echo $global_question->get_title( 'display' ); ?></h4>
