<?php

include '../clearent_util.php';

$confirmed = $_POST['confirm'];
if ($confirmed == "true") {
    $plugin_path = $_POST['plugin_dir_path'];
    $cu = new clearent_util();
    $cu->clearLog($plugin_path);
    $cu->logMessage("User requested log file clear.", $plugin_path);
}

$redirct_url = $_POST['redirect_url'] . "options-general.php?page=clearent_option_group&tab=debug_log";

header("Location: " . $redirct_url);
die();

?>