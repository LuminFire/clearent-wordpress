<?php

class transactions {

    public function transaction_detail() {

        $id = $_REQUEST["id"];

        global $wpdb;
        $table_name = $wpdb->prefix . "clearent_transaction";
        $query = "SELECT *
                  FROM $table_name
                  WHERE transaction_id = $id";
        $recordset = $wpdb->get_results($query);
        if (empty($recordset)) {
            // this shouldn't every happen - if we log the transaction, we have an ID
            echo('Transaction detail not available.');
        } else {
            echo('<table class="trans_detail">');
            foreach ($recordset as $r) {
                echo('<tr><td><span class="label">Order ID</span></td><td>' . $r->order_id . '</td><td><span class="label">Invoice</span></td><td>' . $r->invoice . '</td></tr>');
                echo('<tr><td><span class="label">Customer ID</span></td><td>' . $r->customer_id . '</td><td><span class="label">Purchase Order</span></td><td>' . $r->purchase_order . '</td></tr>');
                if($r->sales_tax_amount){
                    $total = number_format((float)$r->amount + (float)$r->sales_tax_amount, 2, '.', '');
                    $amountDisplay = '<span class="label">Amount<br>Sales Tax<br>Total Amount</span></td><td>' . $r->amount . '<br>' . $r->sales_tax_amount . '<br>' . $total;
                }else{
                    $amountDisplay = '<span class="label">Amount</span></td><td>' . $r->amount;
                }

                echo('<tr><td><span class="label">Transaction Type</span></td><td>' . $r->transaction_type . '</td><td>' . $amountDisplay . '</td></tr>');
                echo('<tr><td><span class="label">Card</span></td><td>' . $r->card . '</td><td><span class="label">Card Expire Date</span></td><td>' . $r->exp_date . '</td></tr>');
                echo('<tr><td><span class="label">Result</span></td><td>' . $r->result . '</td><td><span class="label">Result Code</span></td><td>' . $r->result_code . '</td></tr>');
                echo('<tr><td><span class="label">Transaction ID</span></td><td>' . $r->transaction_id . '</td><td><span class="label">Exchange ID</span></td><td>' . $r->exchange_id . '</td></tr>');
                echo('<tr><td><span class="label">Authorization Code</span></td><td>' . $r->authorization_code . '</td><td><span class="label">Email Address</span></td><td>' . $r->email_address . '</td></tr>');
                echo('<tr><td><span class="label">Description</span></td><td>' . $r->description . '</td><td><span class="label">Comments</span></td><td>' . $r->comments . '</td></tr>');
                echo('<tr><td><span class="label">Billing Address</span></td><td>');
                $billingAddress = '';
                if($r->billing_firstname || $r->billing_lastname){
                    $billingAddress .= $r->billing_firstname . ' ' . $r->billing_lastname . '<br>';
                }
                if($r->billing_company){
                    $billingAddress .= $r->billing_company . '<br>';
                }
                if($r->billing_street){
                    $billingAddress .= $r->billing_street . '<br>';
                }
                if($r->billing_street2){
                    $billingAddress .= $r->billing_street2 . '<br>';
                }
                if($r->billing_city || $r->billing_state || $r->billing_zip){
                    $billingAddress .= $r->billing_city . ', ' . $r->billing_state . '&nbsp;&nbsp;' . $r->billing_zip . '<br>';
                }
                if($r->billing_country){
                    $billingAddress .= $r->billing_country . '<br>';
                }
                if($r->billing_phone){
                    $billingAddress .= $r->billing_phone . '<br>';
                }
                echo($billingAddress . '</td>');
                echo('<td><span class="label">Shipping Address</span></td><td>');
                $shippingAddress = '';
                if($r->shipping_firstname || $r->shipping_lastname){
                    $shippingAddress .= $r->shipping_firstname . ' ' . $r->shipping_lastname . '<br>';
                }
                if($r->shipping_company){
                    $shippingAddress .= $r->shipping_company . '<br>';
                }
                if($r->shipping_street){
                    $shippingAddress .= $r->shipping_street . '<br>';
                }
                if($r->shipping_street2){
                    $shippingAddress .= $r->shipping_street2 . '<br>';
                }
                if($r->shipping_city || $r->shipping_state || $r->shipping_zip){
                    $shippingAddress .= $r->shipping_city . ', ' . $r->shipping_state . '&nbsp;&nbsp;' . $r->shipping_zip . '<br>';
                }
                if($r->shipping_country){
                    $shippingAddress .= $r->shipping_country . '<br>';
                }
                if($r->shipping_phone){
                    $shippingAddress .= $r->shipping_phone . '<br>';
                }
                echo($shippingAddress . '</td>');
                echo('<tr><td><span class="label">Date Added</span></td><td>' . $r->date_added . '</td><td><span class="label">Date Modified</span></td><td>' . $r->date_modified . '</td></tr>');
                echo('<tr><td><span class="label">Client IP</span></td><td>' . $r->client_ip . '</td><td><span class="label">User Agent</span></td><td>' . $r->user_agent . '</td></tr>');
                echo('<tr><td><span class="label">Status</span></td><td colspan="3">' . $r->{'display_message'} . '</td></tr>');
            }
            echo('</table>');
        }

    }

}