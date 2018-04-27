<div class="postbox">
    <?php settings_fields('clearent_option_group'); ?>

    <h3>Environment</h3>

    <p>By default, the Clearent Payments plugin will perform all transactions against the production
        environment.
        The plugin may be switched to sandbox environment for testing purposes.
    </p>
    <table class="form-table">
        <tr valign="top">
            <th scope="row">Environment:</th>
            <td>
                <input id="environment_sandbox" type="radio"
                       name="<?php echo $this->option_name ?>[environment]"
                       value="sandbox" <?php checked('sandbox', $options_opts['environment']); ?> />
                <label for="environment_sandbox">Sandbox</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input id="environment_live" type="radio"
                       name="<?php echo $this->option_name ?>[environment]"
                       value="production" <?php checked('production', $options_opts['environment']); ?> />
                <label for="environment_live">Production</label>
            </td>
        </tr>
    </table>

    <h3>Success URL</h3>

    <p>Enter a url for successful transactions (a success page). If no url
        is specified (blank), the user will be redirected to the home page.
    </p>
    <table class="form-table">
        <tr valign="top">
            <th scope="row"><label for="success_url">Success URL:</label></th>
            <td>
                <?php
                $args = array(
                    'depth' => 0,
                    'child_of' => 0,
                    'selected' => $options_opts['success_url'],
                    'echo' => 1,
                    'name' => 'clearent_opts[success_url]',
                    'id' => 'success_url', // string
                    'class' => 'large', // string
                    'show_option_none' => 'Homepage', // string
                    'show_option_no_change' => null, // string
                    'option_none_value' => '-1', // string
                );
                wp_dropdown_pages($args);
                ?>
            </td>
        </tr>
    </table>

    <h3>API Keys</h3>

    <p>Contact <a target="_blank" href="http://developer.clearent.com/getting-started/">Clearent</a>
        to obtain
        API keys for Sandbox (testing) and Production. A Clearent Sandbox Account and a Clearent
        Production
        Account will have different API keys.
    </p>
    <table class="form-table">
        <tr valign="top">
            <th scope="row"><label for="sb_api_key">Sandbox API Key</label></th>
            <td><input type="text" class="large" id="sb_api_key"
                       name="<?php echo $this->option_name ?>[sb_api_key]"
                       value="<?php echo $options_opts['sb_api_key']; ?>"/></td>
        </tr>
        <tr valign="top">
            <th scope="row"><label for="prod_api_key">Production API Key</label></th>
            <td><input type="text" class="large" id="prod_api_key"
                       name="<?php echo $this->option_name ?>[prod_api_key]"
                       value="<?php echo $options_opts['prod_api_key']; ?>"/></td>
        </tr>
    </table>

    <h3>Debug Logging</h3>

    <p>Enable debug to help diagnose issues or if instructed by Clearent support. <span class="warning">Debug mode can
        quickly fill up php logs and should be disabled unless debugging a specific issue.</span></p>
    <table class="form-table">
        <tr valign="top">
            <th scope="row">Enable Debug Logging?</th>
            <td>
                <input id="enable_debug_disabled" type="radio"
                       name="<?php echo $this->option_name ?>[enable_debug]"
                       value="disabled" <?php checked('disabled', $options_opts['enable_debug']); ?> />
                <label for="enable_debug_disabled">Disabled</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input id="enable_debug_enabled" type="radio"
                       name="<?php echo $this->option_name ?>[enable_debug]"
                       value="enabled" <?php checked('enabled', $options_opts['enable_debug']); ?> />
                <label for="enable_debug_enabled">Enabled</label>


            </td>
        </tr>
    </table>
</div>