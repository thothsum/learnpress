<?php

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

?>
<div id="learn-press-statistic" class="learn-press-statistic-exports">
    <div class="statistic-exports__heading">
        <label for="export-select"><?php echo esc_html__('Export data by: ','learnpress'); ?></label>
        <select name="export-data" id="export-select">
            <option value="users"><?php echo esc_html__('Users','learnpress'); ?></option>
            <option value="courses"><?php echo esc_html__('Courses','learnpress'); ?></option>
            <option value="orders"><?php echo esc_html__('Orders','learnpress'); ?></option>
        </select>
    </div>
    <div class="statistic-exports__directional">
        <div class="statistic-directional__loading">
            <div class="spinner"></div>
        </div>
        <ul>
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
        </ul>
        <div class="statistic-exports__showpreview">
            <button type="button"><?php echo esc_html__('Show results','learnpress'); ?></button>
        </div>
    </div>
    <div class="statistic-exports_data">
        <div class="statistic-exports__loading">
            <div class="spinner"></div>
        </div>
        <div class="statistic-exports__content">
            <h3><?php echo esc_html__('Please select the data to display and then click "show results" button','learnpress'); ?></h3>
        </div>
        <div class="statistic-exports__button">
            <a href="javascript:void(0)" onclick='downloadCSV({ filename: "learnpress_statistic_data.csv" });' title="<?php echo esc_html__('Download CSV','learnpress'); ?>"><?php echo esc_html__('Download CSV','learnpress'); ?></a>
        </div>
    </div>
</div>

