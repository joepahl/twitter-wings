<?php
// don't load directly
if (!function_exists('is_admin')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}
delete_option('twitter_wings_options');
delete_option('tw_tweet_option_cache');	

delete_transient('tw_tweet_cache');
?>