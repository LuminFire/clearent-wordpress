<div class="postbox">
    <h3>Transaction History</h3>
    <?php

    $options = get_option("clearent_opts");

    $payment_data = array();
    if ($options['environment'] == "sandbox") {
        $mode = "sandbox";
    } else {
        $mode = "production";
    }

    global $wpdb;
    $table_name = $wpdb->prefix . "clearent_transaction";
    $query = "SELECT *
             FROM $table_name
             WHERE environment = '$mode'
             AND date_added > NOW() - INTERVAL 90 DAY
             ORDER BY date_added DESC";
    $recordset = $wpdb->get_results($query);
    if ($mode == "sandbox") {
        echo('<p class="warning">');
    } else {
        echo('<p>');
    }
    echo('Application is in ' . $mode . ' mode. Viewing transactions for ' . $mode . '.</p>');
    if (empty($recordset)) {
        echo('There are no transctions to display.');
    } else {
        echo('<p>Below is a list of transactions in the last 90 days.  Most recent transactions are listed first.');
        echo('<br>Additional transactions can be accessed in your application database; up to 13 months previous transactions are available through Clearent\'s Virtual Terminal.</p>');
        echo('<table class="trans_history">');
        echo('  <tr>');
        echo('    <th>order id</th>');
        echo('    <th>summary</th>');
        echo('    <th>email</th>');
        echo('    <th>billing address</th>');
        echo('    <th>shipping address</th>');
        echo('    <th>date (utc)</th>');
        echo('</tr>');

        foreach ($recordset as $r) {
            echo('  <tr onclick="showDetails(\'' . $r->transaction_id . '\')">');
            echo('    <td>' . $r->order_id . '</td>');
            $error_style = '';
            if ($r->result != "APPROVED") {
                $error_style = ' error ';
            }
            $message = '';
            $message .= '<span class="label' . $error_style . '">Result:</span><span class="' . $error_style . '">' . $r->result . '</span><br>';
            $message .= '<span class="label' . $error_style . '">Status:</span><span class="' . $error_style . '">' . $r->{'result_code'} . ' - ' . $r->{'display_message'} . '</span><br>';
            $message .= '<span class="label">Exchange ID:</span>' . $r->{'exchange_id'} . '<br>';
            $message .= '<span class="label">Transaction ID:</span>' . $r->{'transaction_id'} . '<br>';
            $message .= '<span class="label">Authorization Code:</span>' . $r->{'authorization-code'} . '<br>';
            $message .= '<span class="label">Amount:</span>' . $r->amount . '<br>';
            if ($r->sales_tax_amount) {
                $total = number_format((float)$r->amount + (float)$r->sales_tax_amount, 2, '.', '');
                $message .= '<span class="label">Sales Tax:</span>' . $r->sales_tax_amount . '<br>';
                $message .= '<span class="label">Total Amount:</span>' . $total . '<br>';
            }
            $message .= '<span class="label">Card:</span>' . $r->card . '<br>';
            $message .= '<span class="label">Expiration Date:</span>' . $r->{'exp_date'};
            echo('    <td>' . $message . '</td>');
            echo('    <td>' . $r->email_address . '</td>');
            $billingAddress = '';
            if ($r->billing_firstname || $r->billing_lastname) {
                $billingAddress .= $r->billing_firstname . ' ' . $r->billing_lastname . '<br>';
            }
            if ($r->billing_company) {
                $billingAddress .= $r->billing_company . '<br>';
            }
            if ($r->billing_street) {
                $billingAddress .= $r->billing_street . '<br>';
            }
            if ($r->billing_street2) {
                $billingAddress .= $r->billing_street2 . '<br>';
            }
            if ($r->billing_city || $r->billing_state || $r->billing_zip) {
                $billingAddress .= $r->billing_city . ', ' . $r->billing_state . '&nbsp;&nbsp;' . $r->billing_zip . '<br>';
            }
            if ($r->billing_country) {
                $billingAddress .= $r->billing_country . '<br>';
            }
            if ($r->billing_phone) {
                $billingAddress .= $r->billing_phone . '<br>';
            }
            echo('    <td>' . $billingAddress . '</td>');
            $shippingAddress = '';
            if ($r->shipping_firstname || $r->shipping_lastname) {
                $shippingAddress .= $r->shipping_firstname . ' ' . $r->shipping_lastname . '<br>';
            }
            if ($r->shipping_company) {
                $shippingAddress .= $r->shipping_company . '<br>';
            }
            if ($r->shipping_street) {
                $shippingAddress .= $r->shipping_street . '<br>';
            }
            if ($r->shipping_street2) {
                $shippingAddress .= $r->shipping_street2 . '<br>';
            }
            if ($r->shipping_city || $r->shipping_state || $r->shipping_zip) {
                $shippingAddress .= $r->shipping_city . ', ' . $r->shipping_state . '&nbsp;&nbsp;' . $r->shipping_zip . '<br>';
            }
            if ($r->shipping_country) {
                $shippingAddress .= $r->shipping_country . '<br>';
            }
            if ($r->shipping_phone) {
                $shippingAddress .= $r->shipping_phone . '<br>';
            }
            echo('    <td>' . $shippingAddress . '</td>');
            echo('    <td><span class="label">created:</span>' . $r->date_added . '<br>'
                . '<span class="label">modified:</span>' . $r->date_modified . '</td>');
            echo('</tr>');
        }

        echo('</table>');

        echo('<div style="display:none;">');
        echo('    <div id="dialog" title="Transaction Detail"></div>');
        echo('</div>');

    }
    ?>
</div>
