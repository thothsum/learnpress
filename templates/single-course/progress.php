<?php
/**
 * @author        ThimPress
 * @package       LearnPress/Templates
 * @version       2.1.6
 */

defined( 'ABSPATH' ) || exit();

$course = LP_Global::course();
$user   = learn_press_get_current_user();
if ( ! $course ) {
	return;
}
$data = $user->get_course_data( get_the_ID() );

$status = $user->get( 'course-status', $course->get_id() );
if ( ! $user->has_enrolled_course( $course->get_id() ) ) {
	return;
}
$force             = isset( $force ) ? $force : false;
$num_of_decimal    = 0;
$result            = $course->evaluate_course_results( null, $force );
$current           = absint( $result );
$passing_condition = round( $course->passing_condition, $num_of_decimal );
$passed            = $current >= $passing_condition;
$heading           = apply_filters( 'learn_press_course_progress_heading', $status == 'finished' ? __( 'Your results', 'learnpress' ) : __( 'Learning progress', 'learnpress' ) );
$course_items      = sizeof( $course->get_curriculum_items() );
$completed_items   = $course->count_completed_items();
$course_results    = $course->evaluate_course_results();
learn_press_debug( $data->get_results() );
?>
<div class="learn-press-course-results-progress">
    <div class="items-progress">
		<?php if ( $heading !== false ): ?>
            <h4 class="lp-course-progress-heading"><?php echo esc_html_e( 'Items completed', 'learnpress' ); ?></h4>
		<?php endif; ?>
        <span class="number"><?php printf( __( '%d of %d items', 'learnpress' ), $data->get_completed_items(), sizeof( $data->get_items() ) ); ?></span>
        <div class="lp-course-progress">
            <div class="lp-progress-bar">
                <div class="lp-progress-value"
                     style="width: <?php echo $course_items ? absint( $completed_items / $course_items * 100 ) : '0'; ?>%;">
                </div>
            </div>
        </div>

    </div>
    <div class="course-progress">
        <h4 class="lp-course-progress-heading">
			<?php esc_html_e( 'Course results', 'learnpress' ); ?>
			<?php if ( $tooltip = learn_press_get_course_results_tooltip( $course->get_id() ) ) { ?>
                <span class="learn-press-tooltip" data-content="<?php echo esc_html( $tooltip ); ?>"></span>
			<?php } ?>
        </h4>
        <div class="lp-course-status">
            <span class="number"><?php echo absint( $data->get_results( 'result' ) ); ?><span
                        class="percentage-sign">%</span></span>
			<?php if ( $grade = $user->get_course_grade( $course->get_id() ) ) { ?>
                <span class="grade <?php echo esc_attr( $grade ); ?>">
				<?php learn_press_course_grade_html( $grade ); ?>
				</span>
			<?php } ?>
        </div>
        <div class="lp-course-progress <?php echo $passed ? ' passed' : ''; ?>" data-value="<?php echo $current; ?>"
             data-passing-condition="<?php echo $passing_condition; ?>">
            <div class="lp-progress-bar">
                <div class="lp-progress-value" style="width: <?php echo $current; ?>%;">
                </div>
            </div>
            <div class="lp-passing-conditional"
                 data-content="<?php printf( esc_html__( 'Passing condition: %s%%', 'learnpress' ), $passing_condition ); ?>"
                 style="left: <?php echo $passing_condition; ?>%;">
            </div>
        </div>
    </div>
</div>