<?php
// don't load directly
if (!function_exists('is_admin')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}
// UPDATE PLUGIN SETTINGS (get rid of all those options)
$options = array();
// Gone for good
delete_option('tw_active_version'); 
delete_option('tw_text_color');
delete_option('tw_link_color');
delete_option('tw_follow_button');
delete_option('tw_user_display');
// Update Options
$options['tw_usernames'] = get_option('tw_usernames');
delete_option('tw_usernames');
$options['tw_hashtag'] = get_option('tw_hashtag');
delete_option('tw_hashtag');
$options['tw_hashes'] = get_option('tw_hashes');
delete_option('tw_hashes');
$options['tw_title'] = get_option('tw_title');
delete_option('tw_title');
$options['tw_number'] = get_option('tw_number');
delete_option('tw_number');
$options['tw_photos'] = get_option('tw_photos');
delete_option('tw_photos');
$options['tw_user_titles'] = get_option('tw_user_titles');
delete_option('tw_user_titles');
$options['tw_time_below'] = get_option('tw_time_below');
delete_option('tw_time_below');
$options['tw_reply'] = get_option('tw_reply');
delete_option('tw_reply');
$options['tw_https'] = get_option('tw_https');
delete_option('tw_https');
$options['tw_screennames'] = get_option('tw_screennames');
delete_option('tw_screennames');
$options['tw_chashes'] = get_option('tw_chashes');
delete_option('tw_chashes');
$options['tw_removehashes'] = get_option('tw_removehashes');
delete_option('tw_removehashes');
$options['tw_cache'] = get_option('tw_cache');
delete_option('tw_cache');
$options['tw_cache_time'] = get_option('tw_cache_time');
delete_option('tw_cache_time');
$options['tw_styles'] = get_option('tw_styles');
delete_option('tw_styles');
$options['tw_time_form'] = get_option('tw_time_form');
delete_option('tw_time_form');
$options['tw_follow'] = get_option('tw_follow');
delete_option('tw_follow');
$options['tw_follow_move'] = get_option('tw_follow_move');
delete_option('tw_follow_move');
$options['tw_follow_name'] = get_option('tw_follow_name');
delete_option('tw_follow_name');
$options['tw_follow_count'] = get_option('tw_follow_count');
delete_option('tw_follow_count');
$options['tw_follow_lang'] = get_option('tw_follow_lang');
delete_option('tw_follow_lang');

//flip 
if (get_option('tw_retweet')) {
	$options['tw_retweet'] = '';
} else {
	$options['tw_retweet'] = '1';
}
delete_option('tw_retweet');

// New
$options['tw_follow_display_name'] = 'on';

add_option('twitter_wings_options', $options);	
?>