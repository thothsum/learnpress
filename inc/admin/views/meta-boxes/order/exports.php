<?php
/**
 * Admin View: Order export Meta box
 */

/**
 * Prevent loading this file directly
 */
defined('ABSPATH') || exit();
if (!isset($order)) {
    return;
}
if (isset($order_items)) {
    $currency_symbol = learn_press_get_currency_symbol($order_items->currency);
} else {
    $currency_symbol = learn_press_get_currency_symbol();
}

// get script learnpress js pdf
wp_enqueue_script('learnpress-jspdf');
?>

<?php global $post; ?>

<div class="submitbox" id="order-export">
    <div id="order-export__section">
        <div id="editor"></div>
        <button data-id="<?php echo esc_attr($order->get_id()); ?>" type="button"
                id="order-export__button"><?php echo esc_html__('PDF Invoice', 'learnpress') ?></button>
    </div>
</div>

<!-- The Modal -->
<div id="myModal" class="modal export-modal" style="display:none;">

    <!-- Modal content -->
    <div class="modal-content">
        <span class="close">&times;</span>

        <!-- Tab links -->
        <div class="tabs" id="order-export__tabs">
            <div class="preview-tab tab active"
                 data-target="pdf-preview"><?php echo esc_html__('PDF Preview', 'learnpress') ?></div>
            <div class="options-tab tab"
                 data-target="export-options"><?php echo esc_html__('Export Options', 'learnpress') ?></div>
        </div>
        <div id="panels">
            <div class="pdf-preview panel active">
                <!--Start print invoice-->
                <div id="lp-invoice">
                    <div id="lp-invoice__content">
                        <div class="lp-invoice__header">
                            <div class="lp-invoice__hleft">
                                <h1><?php echo esc_html__('Invoice', 'learnpress'); ?></h1>
                            </div>
                            <div class="lp-invoice__hright">
                                <p><?php echo esc_html(get_bloginfo('name')); ?></p>
                                <p class="date"><?php echo esc_html__('Order Date: ', 'learnpress'); ?><?php echo date('d-m-Y h:i:s', esc_attr($order->get_order_date('timestamp'))); ?></p>
                                <p class="invoice-no"><?php echo esc_html__('Invoice No.: ', 'learnpress'); ?><?php echo esc_html($order->get_order_number()); ?></p>
                                <p class="invoice-customer"><?php echo esc_html__('Customer: ', 'learnpress'); ?><?php echo esc_html($order->get_customer_name()); ?></p>
                                <p class="invoice-email"><?php echo esc_html__('Email: ', 'learnpress'); ?><?php echo esc_html($order->get_user('email')); ?></p>
                                <p class="invoice-method">
                                    <?php
                                    $method_title = $order->get_payment_method_title();
                                    $user_ip = $order->get_user_ip_address();
                                    if ($method_title && $user_ip) {
                                        printf('Pay via <strong>%s</strong> at <strong>%s</strong>', apply_filters('learn-press/order-payment-method-title', $method_title, $order), $user_ip);
                                    } elseif ($method_title) {
                                        printf('Pay via <strong>%s</strong>', apply_filters('learn-press/order-payment-method-title', $method_title, $order));
                                    } elseif ($user_ip) {
                                        printf('User IP <strong>%s</strong>', $user_ip);
                                    } ?>
                                </p>
                            </div>
                        </div>
                        <div class="lp-invoice__body">
                            <h4 class="order-data-heading"><?php echo esc_html__('Order details', 'learnpress'); ?></h4>
                            <div class="order-items">
                                <table id="tab_customers" class="table table-striped list-order-items">
                                    <colgroup>
                                        <col width="40%">
                                        <col width="20%">
                                        <col width="20%">
                                        <col width="20%">
                                    </colgroup>
                                    <thead>
                                    <tr class='warning'>
                                        <th class="column-name"><?php _e('Item', 'learnpress'); ?></th>
                                        <th class="column-price"><?php _e('Cost', 'learnpress'); ?></th>
                                        <th class="column-quantity"><?php _e('Quantity', 'learnpress'); ?></th>
                                        <th class="column-total align-right"><?php _e('Amount', 'learnpress'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $filter = array(
                                        'p' => 0,
                                        'limit' => '9999'
                                    );
                                    if ($items = $order->get_items_filter($filter)): ?>
                                        <?php foreach ($items as $item) : ?>
                                            <tr>
                                                <td><?php echo esc_html($item['name']); ?></td>
                                                <td><?php echo learn_press_format_price(isset($item['total']) ? $item['total'] : 0, isset($currency_symbol) ? $currency_symbol : '$'); ?></td>
                                                <td>
                                                    x <?php echo isset($item['quantity']) ? $item['quantity'] : 0; ?></td>
                                                <td><?php echo learn_press_format_price(isset($item['total']) ? $item['total'] : 0, isset($currency_symbol) ? $currency_symbol : '$'); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td><?php echo esc_html__('Sub Total', 'learnpress'); ?></td>
                                        <td><?php echo learn_press_format_price($order->order_subtotal, $currency_symbol); ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td><?php _e('Total', 'learnpress'); ?></td>
                                        <td><?php echo learn_press_format_price($order->order_total, $currency_symbol); ?></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="lp-invoice__actions">
                        <button type="button"
                                id="lp-invoice__export"><?php echo esc_html__('Export to pdf', 'learnpress') ?></button>
                    </div>
                </div>
                <!--End print invoice-->
            </div>
            <div class="export-options panel">
                <div class="export-options__content">
                    <h5>Please select the fields you want to display</h5>
                    <div class="export-options__select">
                        <input type="checkbox" name="site_title" value="" checked="checked">
                        <label for="order_date"> <?php echo esc_html__('Site Title', 'learnpress'); ?></label><br>
                        <input type="checkbox" name="order_date" value="" checked="checked">
                        <label for="order_date"> <?php echo esc_html__('Order Date', 'learnpress'); ?></label><br>
                        <input type="checkbox" name="invoice_no" value="" checked="checked">
                        <label for="order_date"> <?php echo esc_html__('Invoice No.', 'learnpress'); ?></label><br>
                        <input type="checkbox" name="order_customer" value="" checked="checked">
                        <label for="order_date"> <?php echo esc_html__('Customer', 'learnpress'); ?></label><br>
                        <input type="checkbox" name="order_email" value="" checked="checked">
                        <label for="order_date"> <?php echo esc_html__('Email', 'learnpress'); ?></label><br>
                        <input type="checkbox" name="order_payment" value="" checked="checked">
                        <label for="order_payment"> <?php echo esc_html__('Payment Medthod', 'learnpress'); ?></label>
                    </div>
                    <div class="export-options__loading">
                        <div class="spinner"></div>
                    </div>
                </div>
                <div class="export-options__actions">
                    <button type="button" data-id="<?php echo esc_attr($order->get_id()); ?>"
                            id="lp-invoice__update"><?php echo esc_html__('Update', 'learnpress') ?></button>
                </div>
            </div>
        </div>

        <!--End print invoice-->
    </div>

</div>

