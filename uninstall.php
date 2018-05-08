<?php
// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
 
$eiq_option_name = 'eiq_settings';
 
delete_option($eiq_option_name);