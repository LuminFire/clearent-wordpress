<?php

class settings {

    protected $option_name = 'clearent_opts';

    // Generate admin options page
    /**
     *
     */
    public function settingsPage() {

        $file = dirname(dirname(__FILE__)) . '/main.php';
        $plugin_url = plugin_dir_url($file);
        $plugin_path = plugin_dir_path($file);
        $logfile = $plugin_path . "log/debug.log";

        $options_opts = get_option($this->option_name);

        // set up directories
        $plugins_url = plugins_url();
        $get_admin_url = get_admin_url();

        //$trans_path = $plugins_url . "/clearent-payments/clearent/transaction.php";
        //$js_path = $plugins_url . "/clearent-payments/js/";
        $css_path = $plugins_url . "/clearent-payments/css/";
        //$image_path = $plugins_url . "/clearent-payments/image/";

        $trans_url = $get_admin_url . 'admin-post.php';

        wp_enqueue_script('jquery-ui-dialog');
        wp_enqueue_style('jquery-ui', $css_path . 'jquery-ui.min.css');

        ?>
        <div class="wrap">
            <h2><?php echo('Clearent Payments'); ?></h2>

            <?php
            $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'api_keys';
            ?>
            <script type="text/javascript">
                var trans_url = "<?php echo($trans_url) ?>";
            </script>
            <h2 class="nav-tab-wrapper">
                <a href="?page=clearent_option_group&tab=plugin_settings"
                   class="nav-tab <?php echo $active_tab == 'plugin_settings' ? 'nav-tab-active' : ''; ?>">Plugin
                    Settings</a>
                <a href="?page=clearent_option_group&tab=transaction_history"
                   class="nav-tab <?php echo $active_tab == 'transaction_history' ? 'nav-tab-active' : ''; ?>">Transaction
                    History</a>
                <a href="?page=clearent_option_group&tab=active_forms"
                   class="nav-tab <?php echo $active_tab == 'active_forms' ? 'nav-tab-active' : ''; ?>">Pages Using
                    Plugin</a>
                <a href="?page=clearent_option_group&tab=debug_log"
                   class="nav-tab <?php echo $active_tab == 'debug_log' ? 'nav-tab-active' : ''; ?>">Debug Log</a>
            </h2>

            <form name="clearent_clear_log" method="post"
                  action="<?php echo plugin_dir_url(__FILE__) ?>clearent_clear_log.php">
                <input type="hidden" name="confirm" value="true"/>
                <input type="hidden" name="plugin_dir_path" value="<?php echo $plugin_path ?>"/>
                <input type="hidden" name="redirect_url" value="<?php echo get_admin_url() ?>"/>
            </form>

            <form method="post" action="options.php">

                <?php
                if ($active_tab == 'debug_log') {
                    // Debug Log Tab
                    include "debug_log.php";
                } elseif ($active_tab == 'transaction_history') {
                    // Transaction History Tab
                    include "transaction_history.php";
                } elseif ($active_tab == 'active_forms') {
                    // Pages Using Plugin Tab
                    include "pages_using_plugin.php";
                } else {
                    // Plugin Settings tab (default tab)
                    include "plugin_settings.php";
                }
                ?>
                <p class="submit">
                    <input type="submit" class="button-primary" value="Save Changes"/>
                </p>
            </form>

        </div> <!-- End wrap -->
        <?php

    }

}