<?php
    $file = dirname(dirname(__FILE__)) . '/main.php';
    $plugin_url = plugin_dir_url($file);
    $plugin_path = plugin_dir_path($file);
    $logfile = $plugin_path . "log/debug.log";
?>
<script>
    plugin_path = "<?php echo $plugin_url ?>";
</script>
<div id="dialogConfirm" title="Confirmation Required">
    <p>This will clear the debug log. <br>This action cannot be undone.</p>
</div>

<div class="postbox">

    <input type="button" value="Clear Debug Log File" onclick="showConfirmation();"/>
    <br><br>

    <div class="logbox">
        <?php

        if (file_exists($logfile)) {
            echo "[" . $logfile . "]";
            echo "<br><br>";
            echo nl2br(file_get_contents($logfile));
        } else {
            echo "Debug log file does not exist. Turn on debug logging in settings tab to enable debug logs.";
        }

        ?>
    </div>
</div>