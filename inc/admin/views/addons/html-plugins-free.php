<?php
/**
 * Admin View: Displaying all LearnPress's free add-ons.
 *
 * @author  ThimPress
 * @package LearnPress/Views
 * @version 4.0.8
 */

defined( 'ABSPATH' ) || exit();

$last_checked = LP_Background_Query_Items::instance()->get_last_checked( 'plugins_tp' );
$check_url    = wp_nonce_url( add_query_arg( 'force-check-update', 'yes' ), 'lp-check-updates' );
?>

<p><?php printf( __( 'Last checked %s. <a href="%s">Check again</a>', 'learnpress' ), human_time_diff( $last_checked ), $check_url ); ?></p>

<div id="lp-addons-free">
<?php
include learn_press_get_admin_view( 'addons/html-loop-pluginx' );
?>
</div>



