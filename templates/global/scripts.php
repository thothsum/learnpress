<?php
/**
 * Template for add script.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/global/script.php.
 *
 * @author  ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();
if( ! isset($code) ){
    return;
}
?>

<script type="text/javascript">
    jQuery(function ($) {
		<?php echo $code;?>
    });
</script>
