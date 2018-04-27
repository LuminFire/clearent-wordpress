<?php

/**
 * Plugin Name: Clearent Payments
 * Plugin URI: https://wordpress.org/plugins/clearent-payments/
 * Description: Quickly and easily add secure, PCI Compliant, payment to your WordPress site. This plugin is maintained directly by Clearent, a leader in payments.
 * Version: 1.8
 * Author: Clearent, LLC.
 * Author URI: http://clearent.github.io/wordpress/
 */
define('WP_DEBUG', true);
const PLUGIN_VERSION = 1.8;

class wp_clearent {

    const TESTING_API_URL = "https://gateway-dev.clearent.net/rest/v2/transactions";
    const SANDBOX_API_URL = "https://gateway-sb.clearent.net/rest/v2/transactions";
    const PRODUCTION_API_URL = "https://gateway.clearent.net/rest/v2/transactions";

    protected $option_name = 'clearent_opts';

    public function __construct() {
        require_once('admin/admin.php');
        require_once('admin/transactions.php');
        require_once('clearent_util.php');
        require_once('payment/payment.php');

        $admin = new admin();
        $this->clearent_util = new clearent_util();
        $transactions = new transactions();
        $payment = new payment();

        // seession management needed for this plugin
        add_action('init', array($this, 'myStartSession'));                               // Used to create a session for storing tranaaction data
        add_action('wp_login', array($this, 'myEndSession'));                             // Used to destroy session after login
        add_action('wp_logout', array($this, 'myEndSession'));                            // Used to destroy session after logout
        // registration hooks
        register_activation_hook(__FILE__, array($admin, 'activate'));                    // Activate plugin
        register_activation_hook(__FILE__, array($admin, 'install_db'));                  // Create database tables
        // admin hooks
        add_action('admin_menu', array($admin, 'admin_menu'));                            // Creates admin menu page and conditionally loads scripts and styles on admin page
        add_action('admin_init', array($admin, 'registerSettings'));                      // Used for registering settings
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($admin, 'add_action_links'));
        // transaction detail in settings, hooks
        //add_action('admin_post_nopriv_transaction_detail', array($transactions, 'transaction_detail'));     // hook for transaction calls - non-logged in user
        add_action('admin_post_transaction_detail', array($transactions, 'transaction_detail'));    // hook for transaction calls - logged in user
        // transaction hooks
        add_action('admin_post_transaction', array($payment, 'validate'));                   // hook for transaction calls - logged in user
        add_action('admin_post_nopriv_transaction', array($payment, 'validate'));            // hook for transaction calls - non-logged in user
        // shortcode hooks
        add_shortcode('clearent_pay_form', array($payment, 'clearent_pay_form'));            // builds content for embedded form

    }

    // attempt to create a session
    function myStartSession() {
        if (!session_id()) {
            session_start();
        }
    }

    function myEndSession() {
        session_destroy();
    }

}

$wp_clearent = new wp_clearent();
