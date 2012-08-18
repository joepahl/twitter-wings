<?php
if (!function_exists('is_admin')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if ( ! current_user_can('update_plugins') )
	wp_die(__('You are not allowed to update plugins on this blog.'));

global $twitter_wings;

if (isset($_POST['save_options'])) {
	check_admin_referer('twitter_wings');

	$location = "options-general.php?page=twitter-wings"; // based on the location of your sub-menu page
	if ( $referer = wp_get_referer() ) {
		if ( FALSE !== strpos( $referer, $location ) ) 
			$location = remove_query_arg( array( 'message' ), $referer );
	}

	// clear $_POST array if needed
	unset($_POST['_wpnonce'], $_POST['_wp_http_referer'], $_POST['save_options']);
	
	// Update options
	update_option('twitter_wings_options', $_POST);
	
	// Delete cache
	delete_transient('tw_tweet_cache');
	
	$location = add_query_arg('message', 1, $location);
	
	// redirect after header definitions - cannot use wp_redirect($location);			
	$twitter_wings->javascript_redirect($location);
	exit;
}

$options = get_option('twitter_wings_options');

$messages[1] = __('Twitter Wings settings updated.', 'twitter-wings');

if ( isset($_GET['message']) && (int) $_GET['message'] ) {
	$message = $messages[$_GET['message']];
	$_SERVER['REQUEST_URI'] = remove_query_arg(array('message'), $_SERVER['REQUEST_URI']);
}

$title = __('Twitter Wings', 'twitter-wings'); ?>

<div id="twitter-wings-admin" class="wrap">
	<header>
	
		<?php screen_icon(); ?>
		<h2><?php echo esc_html( $title ); ?></h2>
		
		<?php if ( !empty($message) ) : ?>
			<div id="message" class="updated fade"><p><?php echo $message; ?></p></div>
		<?php endif; ?>	
	
	</header>
	
	<div class="tw-rate-limit tw-admin-box">
		
		<h3 class="widget-title" style="clear: left;"><?php _e('Rate Limit Status', 'twitter-wings'); ?></h3>
		
		<?php $json = @file_get_contents('https://api.twitter.com/1/account/rate_limit_status.json');
			
		$tw_limit = json_decode($json);
		
		if ($tw_limit) {
			$tz = get_option('gmt_offset') * 3600;
			$time = date('M j\, Y g:i a', ($tw_limit->reset_time_in_seconds + $tz));
			$re_time_m = floor(($tw_limit->reset_time_in_seconds - time()) / 60);
			$re_time_s = ($tw_limit->reset_time_in_seconds - time()) % 60;
			
			$out =  '<p><strong>' . __('Hourly limit: ', 'twitter-wings') . '</strong>' . $tw_limit->hourly_limit . "</p>\n";
			$out .= '<p><strong>' . __('Remaining hits: ', 'twitter-wings') . '</strong>' . $tw_limit->remaining_hits . "</p>\n";
			$out .= '<p><strong>' . __('Next Reset: ', 'twitter-wings') . '</strong>' . $time . "</p>\n";
			$out .= '<p style="margin-bottom: 0;"><span class="desc">Your server has accessed Twitter\'s API ' . ($tw_limit->hourly_limit - $tw_limit->remaining_hits) . ' times this hour. The rate limit will be reset in ' . $re_time_m . ' min, ' . $re_time_s . "s.</span></p>";
		}
		
		$out = ($out) ? $out : '<p>' . __('Whoops! Looks like something went wrong. Twitter will be back shortly.', 'twitter-wings') . '</p>';
		
		echo $out; ?>
		
	</div>
	
	<div class="tw-feed tw-admin-box">
		
		<?php TwitterWings(array('before_title' => '<h3>', 
								'after_title' => '</h3>', 
								'before_widget' => '<div id="twitter-wings" class="tw-wrapper">', 
								'after_widget' => '</div>'
								)); ?>
	
	</div>
	
	<form method="post" name="twitter_wings" id="twitter_wings_form">

		<?php wp_nonce_field('twitter_wings'); ?>
		
	<h3><?php _e('General Settings', 'twitter-wings'); ?></h3>

		<p class="formrow">
			<label for="usernames"><?php _e('Username(s):', 'twitter-wings'); ?></label>
			<input type="text" id="usernames" name="tw_usernames" class="tw_input" value="<?php echo $options['tw_usernames']; ?>" />
			<span class="desc"><?php _e('Separate multiple usernames with a comma. (default: joepahl, bsdeluxe)', 'twitter-wings'); ?></span>
		</p>
				
		<p class="formrow">		
			<label for="number"><?php _e('Number of Tweets:', 'twitter-wings'); ?></label>
			<input type="number" min="1" max="50" id="number" name="tw_number" class="tw_input short" value="<?php echo $options['tw_number']; ?>" />
			<span class="desc"><?php _e('Number of Tweets to display. (default: 15)', 'twitter-wings'); ?></span>								
		</p>
		
		<p class="formrow">		
			<label for="hashes"><?php _e('Widget title:', 'twitter-wings'); ?></label>
			<input type="text" id="hashes" name="tw_title" class="tw_input" value="<?php echo ($options['tw_title']) ? $options['tw_title'] : 'Twitter'; ?>" />
			<span class="desc"><?php _e('Title in header before Twitter posts. (default: Twitter)', 'twitter-wings'); ?></span>								
		</p>
		
	<h3><?php _e('Custom Display', 'twitter-wings'); ?></h3>
		
		<p class="formrow">
			<input type="checkbox" id="titles" name="tw_user_titles"<?php if ($options['tw_user_titles']) { ?> checked<?php } ?> />
			<label class="radio-label" for="titles"><?php _e('Display name', 'twitter-wings'); ?></label>
			<span class="desc"><?php _e('This option will show user name with every message. (default: on)', 'twitter-wings'); ?></span>					
		</p>
		
		<p class="formrow">		
			<input type="checkbox" id="photos" name="tw_photos"<?php if ($options['tw_photos']) { ?> checked<?php } ?> />
			<label class="radio-label" for="photos"><?php _e('Display avatar', 'twitter-wings'); ?></label>
			<span class="desc"><?php _e('This option will show user photo with every message. (default: on)', 'twitter-wings'); ?></span>					
		</p>
		
		<p class="formrow">		
			<label for="time_form"><?php _e('Timestamp format:', 'twitter-wings'); ?></label>
			<input type="text" id="time_form" name="tw_time_form" class="tw_input" value="<?php echo ($options['tw_time_form']) ? $options['tw_time_form'] : 'M j, Y g:i a'; ?>" />
			<span class="desc"><?php _e('Use php date formatting. (default: M j, Y g:i a)', 'twitter-wings'); ?></span>					
		</p>
		
		<p class="formrow">		
			<label style="margin-bottom: 5px;"><?php _e('Timestamp position:', 'twitter-wings'); ?></label>
			<input type="radio" id="time_above" name="tw_time_below" value=""<?php if (!$options['tw_time_below']) { ?> checked<?php } ?> /> <label for="time_above" class="radio-label"><?php _e('Above Tweet', 'twitter-wings'); ?></label>
			<input type="radio" id="time_below" name="tw_time_below" value="below"<?php if ($options['tw_time_below']) { ?> checked<?php } ?> /> <label for="time_below" class="radio-label"><?php _e('Below Tweet', 'twitter-wings'); ?></label>
			<span class="desc"><?php _e('(default: below)', 'twitter-wings'); ?></span>					
		</p>
		
		<p class="formrow">
			<input type="checkbox" id="retweet" name="tw_retweet"<?php if ($options['tw_retweet']) { ?> checked<?php } ?> />	
			<label class="radio-label" for="retweet"><?php _e('Show Retweets', 'twitter-wings'); ?></label>
			<span class="desc"><?php _e('Display native retweets. (default: on)', 'twitter-wings'); ?></span>				
		</p>
		
		<p class="formrow">
			<input type="checkbox" id="reply" name="tw_reply"<?php if ($options['tw_reply']) { ?> checked<?php } ?> />
			<label class="radio-label" for="reply"><?php _e('Show replies', 'twitter-wings'); ?></label>
			<span class="desc"><?php _e('Display \'@\' replies. (default: off)', 'twitter-wings'); ?></span>				
		</p>
				
		<p class="formrow">
			<input type="checkbox" id="https" name="tw_https"<?php if ($options['tw_https']) { ?> checked<?php } ?> />	
			<label class="radio-label" for="https"><?php _e('Link http://', 'twitter-wings'); ?></label>
			<span class="desc"><?php _e('Create hyperlinks within Tweets. (default: on)', 'twitter-wings'); ?></span>				
		</p>
		
		<p class="formrow">
			<input type="checkbox" id="screennames" name="tw_screennames"<?php if ($options['tw_screennames']) { ?> checked<?php } ?> />	
			<label class="radio-label" for="screennames"><?php _e('Link screen names', 'twitter-wings'); ?></label>
			<span class="desc"><?php _e('Link \'@\' screen names within Tweets. (default: ON)', 'twitter-wings'); ?></span>				
		</p>
		
		<p class="formrow">
			<input type="checkbox" id="chashes" name="tw_chashes"<?php if ($options['tw_chashes']){ ?> checked<?php } ?> />	
			<label class="radio-label" for="chashes"><?php _e('Link hashtags', 'twitter-wings'); ?></label>
			<span class="desc"><?php _e('Link \'#\' hashtags within Tweets. (default: ON)', 'twitter-wings'); ?></span>				
		</p>
		
		<p class="formrow">		
			<input type="checkbox" id="removehashes" name="tw_removehashes"<?php if ($options['tw_removehashes']) { ?> checked<?php } ?> />
			<label class="radio-label" for="removehashes"><?php _e('Strip hashtags', 'twitter-wings'); ?></label>
			<span class="desc"><?php _e('Remove hashes from list in messages. (default: OFF)', 'twitter-wings'); ?></span>				
		</p>
		
		<p class="formrow hashtag">		
			<input type="checkbox" id="hashtag" name="tw_hashtag"<?php if ($options['tw_hashtag']) { ?> checked<?php } ?> />
			<label class="radio-label" for="hashtag"><?php _e('Filter Tweets by hashtag', 'twitter-wings'); ?></label>
			<span class="desc"><?php _e('Enable hashtag filtering. (default: OFF)', 'twitter-wings'); ?></span>					
		</p>
		
		<!-- Conditional hashtag filtering -->
		<p class="formrow hash-terms">		
			<label for="hashes"><?php _e('Only display Tweets containing these hashtags:', 'twitter-wings'); ?></label>
			<input type="text" placeholder="#hashtag, #twitterwings" id="hashes" name="tw_hashes" class="tw_input" value="<?php echo $options['tw_hashes']; ?>" />
			<span class="desc"><?php _e('Separate hashtags with a comma. (default: NULL)', 'twitter-wings'); ?></span>								
		</p>
		
	<h3><?php _e('Custom Settings', 'twitter-wings'); ?></h3>
				
		<p class="formrow cache">
			<input type="checkbox" id="cache" name="tw_cache"<?php if ($options['tw_cache']) { ?> checked<?php } ?> />	
			<label class="radio-label" for="cache"><?php _e('Enable cache', 'twitter-wings'); ?></label>
			<span class="desc"><?php _e('Enable feed cache. (default: on)', 'twitter-wings'); ?></span>				
		</p>
		
		<!-- Contitional Cache Expiration -->		
		<p class="formrow cache-time">		
			<label for="cache_time"><?php _e('Cache timer:', 'twitter-wings'); ?></label>
			<input type="number" min="1" max="60" id="cache_time" name="tw_cache_time" class="tw_input short" value="<?php echo $options['tw_cache_time']; ?>" />
			<span class="desc"><?php _e('Number of minutes to use cached data before rechecking the Twitter API. (default: 60)', 'twitter-wings'); ?></span>				
		</p>
		
		<p class="formrow">		
			<input type="checkbox" id="styles" name="tw_styles"<?php if ($options['tw_styles']) { ?> checked<?php } ?> />			
			<label class="radio-label" for="styles"><?php _e('Disable stylesheet', 'twitter-wings'); ?></label>
			<span class="desc"><?php _e('Don\'t cramp my style. Disable Twitter Wings stylesheet (default: off)', 'twitter-wings'); ?></span>				
		</p>
		
	<h3><?php _e('Add Follow Button', 'twitter-wings'); ?></h3>
		
		<p class="formrow add-follow">		
			<input type="checkbox" id="follow" name="tw_follow"<?php if ($options['tw_follow']) { ?> checked<?php } ?> />
			<label class="radio-label" for="follow"><?php _e('Add follow button', 'twitter-wings'); ?></label>
			<span class="desc"><?php _e('Add a Twitter follow button to my feed. (default: off)', 'twitter-wings'); ?></span>				
		</p>
		
		<!-- Contitional follow button options -->
		<div class="follow-block">
		
			<p class="formrow">		
				<label for="follow_name"><?php _e('Follow button username:', 'twitter-wings'); ?></label>
				<input type="text" id="follow_name" name="tw_follow_name" class="tw_input" value="<?php echo $options['tw_follow_name']; ?>" />
				<span class="desc"><?php _e('Username for follow buttom.', 'twitter-wings'); ?></span>				
			</p>
			
			<p class="formrow">		
				<input type="checkbox" id="follow_display_name" name="tw_follow_display_name"<?php if ($options['tw_follow_display_name']) { ?> checked<?php } ?> />
				<label class="radio-label" for="follow_display_name"><?php _e('Display username in button', 'twitter-wings'); ?></label>
				<span class="desc"><?php _e('Username will display within the button. (default: checked)', 'twitter-wings'); ?></span>				
			</p>
		
			<p class="formrow">		
				<label><?php _e('Follow button position:', 'twitter-wings'); ?></label>
				<input type="radio" id="above" name="tw_follow_move" value="above"<?php if ($options['tw_follow_move']) { ?> checked<?php } ?> /> <label for="above" class="radio-label"><?php _e('Above feed', 'twitter-wings'); ?></label>
				<input type="radio" id="below" name="tw_follow_move" value=""<?php if (!$options['tw_follow_move']) { ?> checked<?php } ?> /> <label for="below" class="radio-label"><?php _e('Below feed', 'twitter-wings'); ?></label>
				<span class="desc"><?php _e('By default, the follow button will be place below your feed. (default: below)', 'twitter-wings'); ?></span>				
			</p>
			
			<p class="formrow">		
				<label><?php _e('Button size:', 'twitter-wings'); ?></label>
				<input type="radio" id="normal" name="tw_follow_size" value=""<?php if (!$options['tw_follow_size']) { ?> checked<?php } ?> /> <label for="normal" class="radio-label"><?php _e('Normal button', 'twitter-wings'); ?></label>
				<input type="radio" id="big" name="tw_follow_size" value="big"<?php if ($options['tw_follow_size']) { ?> checked<?php } ?> /> <label for="big" class="radio-label"><?php _e('Big button', 'twitter-wings'); ?></label>
				<span class="desc"><?php _e('By default, the follow button will be place below your feed. (default: normal)', 'twitter-wings'); ?></span>				
			</p>
						
			<p class="formrow">
				<input type="checkbox" id="follow_count" name="tw_follow_count"<?php if ($options['tw_follow_count']) { ?> checked<?php } ?> />		
				<label class="radio-label" for="follow_count"><?php _e('Show follow count', 'twitter-wings'); ?></label>
				<span class="desc"><?php _e('Display number of followers. (default: off)', 'twitter-wings'); ?></span>				
			</p>
			
			<p class="formrow last">		
				<label for="follow_lang"><?php _e('Follow language', 'twitter-wings'); ?></label>						
				<select id="follow_lang" name="tw_follow_lang">
					<option value="no"<?php if ($options['tw_follow_lang'] == 'no'){ ?> selected<?php } ?>><?php _e('Norwegian - Norsk', 'twitter-wings'); ?></option>
					<option value="eu"<?php if ($options['tw_follow_lang'] == 'eu'){ ?> selected<?php } ?>><?php _e('Basque - Euskara', 'twitter-wings'); ?></option>
					<option value="zh-tw"<?php if ($options['tw_follow_lang'] == 'zh-tw'){ ?> selected<?php } ?>><?php _e('Traditional Chinese - 繁體中文', 'twitter-wings'); ?></option>
					<option value="zh-cn"<?php if ($options['tw_follow_lang'] == 'zh-cn'){ ?> selected<?php } ?>><?php _e('Simplified Chinese - 简体中文', 'twitter-wings'); ?></option>
					<option value="ur"<?php if ($options['tw_follow_lang'] == 'ur'){ ?> selected<?php } ?>><?php _e('Urdu - اردو', 'twitter-wings'); ?></option>
					<option value="tr"<?php if ($options['tw_follow_lang'] == 'tr'){ ?> selected<?php } ?>><?php _e('Turkish - Türkçe', 'twitter-wings'); ?></option>
					<option value="en"<?php if ($options['tw_follow_lang'] == 'en' || $options['tw_follow_lang'] == ''){ ?> selected<?php } ?>><?php _e('English', 'twitter-wings'); ?></option>
					<option value="ru"<?php if ($options['tw_follow_lang'] == 'ru'){ ?> selected<?php } ?>><?php _e('Russian - Русский', 'twitter-wings'); ?></option>
					<option value="sv"<?php if ($options['tw_follow_lang'] == 'sv'){ ?> selected<?php } ?>><?php _e('Swedish - Svenska', 'twitter-wings'); ?></option>
					<option value="id"<?php if ($options['tw_follow_lang'] == 'id'){ ?> selected<?php } ?>><?php _e('Indonesian - Bahasa Indonesia', 'twitter-wings'); ?></option>
					<option value="it"<?php if ($options['tw_follow_lang'] == 'it'){ ?> selected<?php } ?>><?php _e('Italian - Italiano', 'twitter-wings'); ?></option>
					<option value="da"<?php if ($options['tw_follow_lang'] == 'da'){ ?> selected<?php } ?>><?php _e('Danish - Dansk', 'twitter-wings'); ?></option>
					<option value="fi"<?php if ($options['tw_follow_lang'] == 'fi'){ ?> selected<?php } ?>><?php _e('Finnish - Suomi', 'twitter-wings'); ?></option>
					<option value="de"<?php if ($options['tw_follow_lang'] == 'de'){ ?> selected<?php } ?>><?php _e('German - Deutsch', 'twitter-wings'); ?></option>
					<option value="fil"<?php if ($options['tw_follow_lang'] == 'fil'){ ?> selected<?php } ?>><?php _e('Filipino - Filipino', 'twitter-wings'); ?></option>
					<option value="ko"<?php if ($options['tw_follow_lang'] == 'ko'){ ?> selected<?php } ?>><?php _e('Korean - 한국어', 'twitter-wings'); ?></option>
					<option value="th"<?php if ($options['tw_follow_lang'] == 'th'){ ?> selected<?php } ?>><?php _e('Thai - ภาษาไทย', 'twitter-wings'); ?></option>
					<option value="cs"<?php if ($options['tw_follow_lang'] == 'cs'){ ?> selected<?php } ?>><?php _e('Czech - Čeština', 'twitter-wings'); ?></option>
					<option value="pt"<?php if ($options['tw_follow_lang'] == 'pt'){ ?> selected<?php } ?>><?php _e('Portuguese - Português', 'twitter-wings'); ?></option>
					<option value="el"<?php if ($options['tw_follow_lang'] == 'el'){ ?> selected<?php } ?>><?php _e('Greek - Ελληνικά', 'twitter-wings'); ?></option>
					<option value="msa"<?php if ($options['tw_follow_lang'] == 'msa'){ ?> selected<?php } ?>><?php _e('Malay - Bahasa Melayu', 'twitter-wings'); ?></option>
					<option value="he"<?php if ($options['tw_follow_lang'] == 'he'){ ?> selected<?php } ?>><?php _e('Hebrew - עִבְרִית', 'twitter-wings'); ?></option>
					<option value="es"<?php if ($options['tw_follow_lang'] == 'es'){ ?> selected<?php } ?>><?php _e('Spanish - Español', 'twitter-wings'); ?></option>
					<option value="fr"<?php if ($options['tw_follow_lang'] == 'fr'){ ?> selected<?php } ?>><?php _e('French - français', 'twitter-wings'); ?></option>
					<option value="hi"<?php if ($options['tw_follow_lang'] == 'hi'){ ?> selected<?php } ?>><?php _e('Hindi - हिन्दी', 'twitter-wings'); ?></option>
					<option value="nl"<?php if ($options['tw_follow_lang'] == 'nl'){ ?> selected<?php } ?>><?php _e('Dutch - Nederlands', 'twitter-wings'); ?></option>
					<option value="uk"<?php if ($options['tw_follow_lang'] == 'uk'){ ?> selected<?php } ?>><?php _e('Ukrainian - Українська мова', 'twitter-wings'); ?></option>
					<option value="fa"<?php if ($options['tw_follow_lang'] == 'fa'){ ?> selected<?php } ?>><?php _e('Farsi - فارسی', 'twitter-wings'); ?></option>
					<option value="hu"<?php if ($options['tw_follow_lang'] == 'hu'){ ?> selected<?php } ?>><?php _e('Hungarian - Magyar', 'twitter-wings'); ?></option>
					<option value="pl"<?php if ($options['tw_follow_lang'] == 'pl'){ ?> selected<?php } ?>><?php _e('Polish - Polski', 'twitter-wings'); ?></option>
					<option value="ca"<?php if ($options['tw_follow_lang'] == 'ca'){ ?> selected<?php } ?>><?php _e('Catalan - català', 'twitter-wings'); ?></option>
					<option value="ar"<?php if ($options['tw_follow_lang'] == 'ar'){ ?> selected<?php } ?>><?php _e('Arabic - العربية', 'twitter-wings'); ?></option>
					<option value="ja"<?php if ($options['tw_follow_lang'] == 'ja'){ ?> selected<?php } ?>><?php _e('Japanese - 日本語', 'twitter-wings'); ?></option>
				</select>
				<span class="desc"><?php _e('Language for your button. (default: English)', 'twitter-wings'); ?></span>				
			</p>
		</div>
				
		<p><input type="submit" class="button button-primary" name="save_options" value="<?php _e('Save Options', 'twitter-wings'); ?>" /></p>
	
	</form>
</div>