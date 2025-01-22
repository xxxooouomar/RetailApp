
<?php
function log_error($message) {
    $log_file = __DIR__ . "/error_log.txt";
    $date = date("Y-m-d H:i:s");
    file_put_contents($log_file, "[$date] $message\n", FILE_APPEND);
}
?>
