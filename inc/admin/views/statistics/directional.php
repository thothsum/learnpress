<?php
if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
if ( ! isset( $directional_type ) ) {
	return;
}
switch ($directional_type) {
	case 'users':
	    ?>
        <li class="directional__opt">
            <label for="export-select"><?php echo esc_html__('Select the time period you want to query: ','learnpress'); ?></label>
            <select name="exports-type" id="statistic-exports__type">
                <option value="last-7-days"><?php echo esc_html__('Last 7 Days','learnpress'); ?></option>
                <option value="last-12-months"><?php echo esc_html__('Last 12 Months','learnpress'); ?></option>
                <option value="all"><?php echo esc_html__('All','learnpress'); ?></option>
                <option value="custom-time"><?php echo esc_html__('Custome Date','learnpress'); ?></option>
            </select>
        </li>
        <li class="directional__ctime">
            <form id="user-custom-time">
                <span><?php _e( 'From', 'learnpress' ) ?></span>
                <input id="date-picker" type="text" placeholder="Y/m/d" name="from" class="date-picker" readonly="readonly">
                <span><?php _e( 'To', 'learnpress' ) ?></span>
                <input id="date-picker" type="text" placeholder="Y/m/d" name="to" class="date-picker" readonly="readonly">
                <input type="hidden" name="action" value="learnpress_custom_stats">
            </form>
        </li>
        <li class="directional__role">
            <label for="export-select"><?php echo esc_html__('Select Role: ','learnpress'); ?></label>
            <select name="exports-role" id="statistic-exports__role">
                <option value=""><?php echo esc_html__('All','learnpress'); ?></option>
				<?php
				$editable_roles = get_editable_roles();
				foreach ($editable_roles as $role => $details) {
					?>
                    <option value="<?php echo esc_attr($role); ?>"><?php echo esc_html($details['name']); ?></option>
				<?php } ?>
            </select>
        </li>
<?php
		break;
	case 'courses':
	    ?>
        <li class="directional__opt">
            <label for="export-select"><?php echo esc_html__('Select the time period you want to query: ','learnpress'); ?></label>
            <select name="exports-type" id="statistic-exports__type">
                <option value="last-7-days"><?php echo esc_html__('Last 7 Days','learnpress'); ?></option>
                <option value="last-12-months"><?php echo esc_html__('Last 12 Months','learnpress'); ?></option>
                <option value="all"><?php echo esc_html__('All','learnpress'); ?></option>
                <option value="custom-time"><?php echo esc_html__('Custome Date','learnpress'); ?></option>
            </select>
        </li>
        <li class="directional__ctime">
            <form id="user-custom-time">
                <span><?php _e( 'From', 'learnpress' ) ?></span>
                <input id="date-picker" type="text" placeholder="Y/m/d" name="from" class="date-picker" readonly="readonly">
                <span><?php _e( 'To', 'learnpress' ) ?></span>
                <input id="date-picker" type="text" placeholder="Y/m/d" name="to" class="date-picker" readonly="readonly">
                <input type="hidden" name="action" value="learnpress_custom_stats">
            </form>
        </li>
<?php
		break;
	case 'orders':
	    ?>
        <li class="directional__saleby">
            <label for="sale-by"><?php echo esc_html__('Sale by: ','learnpress'); ?></label>
            <select name="sale-by" id="sale-by">
                <option value="date"><?php echo esc_html__('Date','learnpress'); ?></option>
                <option value="course"><?php echo esc_html__('Course','learnpress'); ?></option>
            </select>
        </li>
        <li class="directional__opt">
            <label for="export-select"><?php echo esc_html__('Select the time period you want to query: ','learnpress'); ?></label>
            <select name="exports-type" id="statistic-exports__type">
                <option value="last-7-days"><?php echo esc_html__('Last 7 Days','learnpress'); ?></option>
                <option value="last-12-months"><?php echo esc_html__('Last 12 Months','learnpress'); ?></option>
                <option value="all"><?php echo esc_html__('All','learnpress'); ?></option>
                <option value="custom-time"><?php echo esc_html__('Custome Date','learnpress'); ?></option>
            </select>
        </li>
        <li class="directional__ctime">
            <form id="user-custom-time">
                <span><?php _e( 'From', 'learnpress' ) ?></span>
                <input id="date-picker" type="text" placeholder="Y/m/d" name="from" class="date-picker" readonly="readonly">
                <span><?php _e( 'To', 'learnpress' ) ?></span>
                <input id="date-picker" type="text" placeholder="Y/m/d" name="to" class="date-picker" readonly="readonly">
                <input type="hidden" name="action" value="learnpress_custom_stats">
            </form>
        </li>
        <li class="directional__course">
            <label for="course-data__ajax"><?php echo esc_html__('Select a course','learnpress') ?></label>
            <select id="course-data__name" class="statistics-search-course postName form-control" style="width:500px" name="postName"></select>

            <?php
			// The Query
			$args = array(
				'post_type' => 'lp_course',
				'posts_per_page' => -1,
				'orderby' => 'title',
				'order' => 'ASC'

			);
			$lp_course_query =  get_posts($args);
			?>
        </li>
<?php
		break;
	default:
	    ?>
        <li class="directional__opt">
            <label for="export-select"><?php echo esc_html__('Select the time period you want to query: ','learnpress'); ?></label>
            <select name="exports-type" id="statistic-exports__type">
                <option value="last-7-days"><?php echo esc_html__('Last 7 Days','learnpress'); ?></option>
                <option value="last-12-months"><?php echo esc_html__('Last 12 Months','learnpress'); ?></option>
                <option value="all"><?php echo esc_html__('All','learnpress'); ?></option>
                <option value="custom-time"><?php echo esc_html__('Custome Date','learnpress'); ?></option>
            </select>
        </li>
        <li class="directional__ctime">
            <form id="user-custom-time">
                <span><?php _e( 'From', 'learnpress' ) ?></span>
                <input id="date-picker" type="text" placeholder="Y/m/d" name="from" class="date-picker" readonly="readonly">
                <span><?php _e( 'To', 'learnpress' ) ?></span>
                <input id="date-picker" type="text" placeholder="Y/m/d" name="to" class="date-picker" readonly="readonly">
                <input type="hidden" name="action" value="learnpress_custom_stats">
            </form>
        </li>
        <li class="directional__role">
            <label for="export-select"><?php echo esc_html__('Select Role: ','learnpress'); ?></label>
            <select name="exports-role" id="statistic-exports__role">
                <option value=""><?php echo esc_html__('All','learnpress'); ?></option>
				<?php
				$editable_roles = get_editable_roles();
				foreach ($editable_roles as $role => $details) {
					?>
                    <option value="<?php echo esc_attr($role); ?>"><?php echo esc_html($details['name']); ?></option>
				<?php } ?>
            </select>
        </li>
<?php
}
?>



