<?php

// If uninstall is not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

// delete plugin options
$option_name = 'clearent_opts';
delete_option($option_name);

// drop custom db table
global $wpdb;
$table_name = $wpdb->prefix . "clearent_transaction";
$wpdb->query("DROP TABLE IF EXISTS $table_name");

?>