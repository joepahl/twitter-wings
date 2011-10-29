<?php
$location = $options_page; // Form Action URI
$ch = '';
?>

<div id="twitter-wings-admin">
	<h1><?php _e('Twitter Wings', 'twitter-wings'); ?></h1>
	
	<form method="post" action="options.php">

		<?php wp_nonce_field('update-options'); ?>

		<div class="formrow first">
			<label for="usernames"><?php _e('Twitter Username(s):', 'twitter-wings'); ?></label>
			<input type="text" id="usernames" name="tw_usernames" class="tw_input" value="<?php echo get_option('tw_usernames'); ?>" />
			<span class="desc"><?php _e('List of Twitter accounts separated with ",". e.g. <i>joepahl,bsdeluxe,dylanized</i> (default: <i>blank</i>)', 'twitter-wings'); ?></span>
		</div>
		
		<div class="formrow">		
			<label for="hashes"><?php _e('Hashes:', 'twitter-wings'); ?></label>
			<input type="text" id="hashes" name="tw_hashes" class="tw_input" value="<?php echo get_option('tw_hashes'); ?>" />
			<span class="desc"><?php _e('Filter tweets by hashtay. Only include links that contain one the follow hashtags. Separate hashtags with ",". e.g. <i>#STL,#fh,#AEA</i> (default: <i>blank</i>)', 'twitter-wings'); ?></span>								
		</div>
		
		<div class="formrow">		
			<label for="hashes"><?php _e('Header title:', 'twitter-wings'); ?></label>
			<input type="text" id="hashes" name="tw_title" class="tw_input" value="<?php echo (get_option('tw_title')) ? get_option('tw_title') : 'Twitter'; ?>" />
			<span class="desc"><?php _e('Title in header before Twitter posts. (default: Twitter)', 'twitter-wings'); ?></span>								
		</div>		

		<div class="formrow">		
			<label for="number"><?php _e('Number of Posts:', 'twitter-wings'); ?></label>
			<input type="text" id="number" name="tw_number" class="tw_input" value="<?php echo get_option('tw_number'); ?>" />
			<span class="desc"><?php _e('Total number of posts. (default: 15)', 'twitter-wings'); ?></span>								
		</div>	
		
		<div class="formrow">		
			<label><?php _e('Show User\'s Photo', 'twitter-wings'); ?></label>
			<?php if(get_option('tw_photos') != ""){ $ch = ' checked'; } else { $ch = ''; } ?>
			<input type="checkbox" id="photos" name="tw_photos"<?php echo $ch ?> />
			<span class="desc"><?php _e('This option will show user photo with every message. (default: on)', 'twitter-wings'); ?></span>					
		</div>
		
		<div class="formrow">		
			<label><?php _e('Show User\'s Name', 'twitter-wings'); ?></label>
			<?php if(get_option('tw_user_titles') != ""){ $ch = ' checked'; } else { $ch = ''; } ?>
			<input type="checkbox" id="titles" name="tw_user_titles"<?php echo $ch ?> />
			<span class="desc"><?php _e('This option will show user name with every message. (default: on)', 'twitter-wings'); ?></span>					
		</div>
		
		<div class="formrow">		
			<label><?php _e('Show Display Name', 'twitter-wings'); ?></label>
			<?php if(get_option('tw_user_display') != ""){ $ch = ' checked'; } else { $ch = ''; } ?>
			<input type="checkbox" id="user_display" name="tw_user_display"<?php echo $ch ?> />
			<span class="desc"><?php _e('This option will show user full name next to their username. Show User\'s Name must be active. (default: off)', 'twitter-wings'); ?></span>					
		</div>
		
		<div class="formrow">		
			<label><?php _e('Format Timestamp', 'twitter-wings'); ?></label>
			<input type="text" id="time_form" name="tw_time_form" class="tw_input" value="<?php echo (get_option('tw_time_form')) ? get_option('tw_time_form') : 'g:i A M d, Y'; ?>" />
			<span class="desc"><?php _e('Use php date formatting. (default: g:i A M d, Y)', 'twitter-wings'); ?></span>					
		</div>
		
		<div class="formrow">		
			<label><?php _e('Timestamp Below Text', 'twitter-wings'); ?></label>
			<?php if(get_option('tw_time_below') != ""){ $ch = ' checked'; } else { $ch = ''; } ?>
			<input type="checkbox" id="time_below" name="tw_time_below"<?php echo $ch ?> />
			<span class="desc"><?php _e('This option move the timestamp, positioning it below the tweet. (default: off)', 'twitter-wings'); ?></span>					
		</div>
		
		<div class="formrow">		
			<label><?php _e('Show Replies', 'twitter-wings'); ?></label>
			<?php if(get_option('tw_reply') != ""){ $ch = ' checked'; } else { $ch = ''; } ?>
			<input type="checkbox" id="reply" name="tw_reply"<?php echo $ch ?> />
			<span class="desc"><?php _e('Include replies. (default: off)', 'twitter-wings'); ?></span>				
		</div>
		
		<div class="formrow">		
			<label><?php _e('Hide Retweets', 'twitter-wings'); ?></label>
			<?php if(get_option('tw_retweet') != ""){ $ch = ' checked'; } else { $ch = ''; } ?>
			<input type="checkbox" id="retweet" name="tw_retweet"<?php echo $ch ?> />
			<span class="desc"><?php _e('Hide native retweets. (default: off)', 'twitter-wings'); ?></span>				
		</div>
		
		<div class="formrow">		
			<label><?php _e('Link http://', 'twitter-wings'); ?></label>
			<?php if(get_option('tw_https') != ""){ $ch = ' checked'; } else { $ch = ''; } ?>
			<input type="checkbox" id="https" name="tw_https"<?php echo $ch ?> />
			<span class="desc"><?php _e('Create hyperlinks from URLs. (default: on)', 'twitter-wings'); ?></span>				
		</div>
		
		<div class="formrow">		
			<label><?php _e('Link @screennames', 'twitter-wings'); ?></label>
			<?php if(get_option('tw_screennames') != ""){ $ch = ' checked'; } else { $ch = ''; } ?>
			<input type="checkbox" id="screennames" name="tw_screennames"<?php echo $ch ?> />
			<span class="desc"><?php _e('Create links from screennames in messages. (default: on)', 'twitter-wings'); ?></span>				
		</div>
		
		<div class="formrow">		
			<label><?php _e('Link #hashes', 'twitter-wings'); ?></label>
			<?php if(get_option('tw_chashes') != ""){ $ch = ' checked'; } else { $ch = ''; } ?>
			<input type="checkbox" id="chashes" name="tw_chashes"<?php echo $ch ?> />
			<span class="desc"><?php _e('Create links from hashes in messages. (default: on)', 'twitter-wings'); ?></span>				
		</div>
		
		<div class="formrow">		
			<label><?php _e('Remove #hashes', 'twitter-wings'); ?></label>
			<?php if(get_option('tw_removehashes') != ""){ $ch = ' checked'; } else { $ch = ''; } ?>
			<input type="checkbox" id="removehashes" name="tw_removehashes"<?php echo $ch ?> />
			<span class="desc"><?php _e('Remove hashes from list in messages. (default: off)', 'twitter-wings'); ?></span>				
		</div>
				
		<div class="formrow">		
			<label><?php _e('Use Cache', 'twitter-wings'); ?></label>
			<?php if(get_option('tw_cache') != ""){ $ch = ' checked'; } else { $ch = ''; } ?>
			<input type="checkbox" id="cache" name="tw_cache"<?php echo $ch ?> />
			<span class="desc"><?php _e('Using cache will improve your page load. Data will be saved in cache at the increment of time set below. (default: on)', 'twitter-wings'); ?></span>				
		</div>
		
		<div class="formrow">		
			<label><?php _e('Cache Expire Time:', 'twitter-wings'); ?></label>
			<input type="number" min="1" max="120" id="cache_time" name="tw_cache_time" class="tw_input short" value="<?php echo get_option('tw_cache_time'); ?>" />
			<span class="desc"><?php _e('Number of minutes to use cached data before rechecking the Twitter API. (default: 60)', 'twitter-wings'); ?></span>				
		</div>
		
		<div class="formrow last">		
			<label><?php _e('Remove Stylesheet', 'twitter-wings'); ?></label>
			<?php if(get_option('tw_styles') != ""){ $ch = ' checked'; } else { $ch = ''; } ?>
			<input type="checkbox" id="styles" name="tw_styles"<?php echo $ch ?> />
			<span class="desc"><?php _e('Remove default css. I\'m using my own stylesheet. Don\'t cramp my style. (default: off)', 'twitter-wings'); ?></span>				
		</div>
		
		<div class="formrow">		
			<label><?php _e('Add Follow Button', 'twitter-wings'); ?></label>
			<?php if(get_option('tw_follow') != ""){ $ch = ' checked'; } else { $ch = ''; } ?>
			<input type="checkbox" id="follow" name="tw_follow"<?php echo $ch ?> />
			<span class="desc"><?php _e('Add a Twitter follow button to my feed. (default: off)', 'twitter-wings'); ?></span>				
		</div>
		
		<div class="formrow">		
			<label><?php _e('Display Follow Button before feed', 'twitter-wings'); ?></label>
			<?php if(get_option('tw_follow_move') != ""){ $ch = ' checked'; } else { $ch = ''; } ?>
			<input type="checkbox" id="follow_move" name="tw_follow_move"<?php echo $ch ?> />
			<span class="desc"><?php _e('By default, the follow button will be place below your feed. (default: off)', 'twitter-wings'); ?></span>				
		</div>
		
		<div class="formrow">		
			<label><?php _e('Follow Username', 'twitter-wings'); ?></label>
			<input type="text" id="follow_name" name="tw_follow_name" class="tw_input" value="<?php echo (get_option('tw_follow_name')) ? get_option('tw_follow_name') : ''; ?>" />
			<span class="desc"><?php _e('Username for follow link. (default: <i>blank</i>)', 'twitter-wings'); ?></span>				
		</div>
		
		<div class="formrow">		
			<label><?php _e('Button Style', 'twitter-wings'); ?></label>
			<?php if(get_option('tw_follow_button') != ""){ $ch = ' checked'; } else { $ch = ''; } ?>
			<input type="checkbox" id="follow_button" name="tw_follow_button"<?php echo $ch ?> />
			<span class="desc"><?php _e('Use gray button instead of default blue. (default: off)', 'twitter-wings'); ?></span>				
		</div>
		
		<div class="formrow">		
			<label><?php _e('Show Follow Count', 'twitter-wings'); ?></label>
			<?php if(get_option('tw_follow_count') != ""){ $ch = ' checked'; } else { $ch = ''; } ?>
			<input type="checkbox" id="follow_count" name="tw_follow_count"<?php echo $ch ?> />
			<span class="desc"><?php _e('Display your followers. (default: off)', 'twitter-wings'); ?></span>				
		</div>
		
		<div class="formrow">		
			<label><?php _e('Text Color', 'twitter-wings'); ?></label>
			<input type="text" id="text_color" name="tw_text_color" class="tw_input" value="<?php echo (get_option('tw_text_color')) ? get_option('tw_text_color') : ''; ?>" />
			<span class="desc"><?php _e('Hexidecimal color code. (default: #800080)', 'twitter-wings'); ?></span>				
		</div>
		
		<div class="formrow last">		
			<label><?php _e('Link Color', 'twitter-wings'); ?></label>
			<input type="text" id="link_color" name="tw_link_color" class="tw_input" value="<?php echo (get_option('tw_link_color')) ? get_option('tw_link_color') : ''; ?>" />
			<span class="desc"><?php _e('Hexidecimal color code. (default: #800080)', 'twitter-wings'); ?></span>				
		</div>
		
		<div class="formrow">		
			<label><?php _e('Follow language', 'twitter-wings'); ?></label>
			
			<?php $opt = (get_option('tw_follow_lang') != "") ? get_option('tw_follow_lang') : $opt = ''; ?>
					
			<select id="follow_lang" name="tw_follow_lang">
				<option value="nl"<?php if($opt == 'nl'){ ?> selected<?php } ?>><?php _e('Dutch', 'twitter-wings'); ?></option>
				<option value="en"<?php if($opt == 'en' || $opt == ''){ ?> selected<?php } ?>><?php _e('English', 'twitter-wings'); ?></option>
				<option value="fr"<?php if($opt == 'fr'){ ?> selected<?php } ?>><?php _e('French', 'twitter-wings'); ?></option>
				<option value="de"<?php if($opt == 'de'){ ?> selected<?php } ?>><?php _e('German', 'twitter-wings'); ?></option>
				<option value="id"<?php if($opt == 'id'){ ?> selected<?php } ?>><?php _e('Indonesian', 'twitter-wings'); ?></option>
				<option value="it"<?php if($opt == 'it'){ ?> selected<?php } ?>><?php _e('Italian', 'twitter-wings'); ?></option>
				<option value="ja"<?php if($opt == 'ja'){ ?> selected<?php } ?>><?php _e('Japanese', 'twitter-wings'); ?></option>
				<option value="ko"<?php if($opt == 'ko'){ ?> selected<?php } ?>><?php _e('Korean', 'twitter-wings'); ?></option>
				<option value="pt"<?php if($opt == 'pt'){ ?> selected<?php } ?>><?php _e('Portuguese', 'twitter-wings'); ?></option>
				<option value="ru"<?php if($opt == 'ru'){ ?> selected<?php } ?>><?php _e('Russian', 'twitter-wings'); ?></option>
				<option value="es"<?php if($opt == 'es'){ ?> selected<?php } ?>><?php _e('Spanish', 'twitter-wings'); ?></option>
				<option value="tr"<?php if($opt == 'tr'){ ?> selected<?php } ?>><?php _e('Turkish', 'twitter-wings'); ?></option>
			</select>
			<span class="desc"><?php _e('Language for your button. (default: English)', 'twitter-wings'); ?></span>				
		</div>

		<input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="tw_usernames,tw_hashes,tw_number,tw_photos,tw_user_titles,tw_screennames,tw_https,tw_chashes,tw_removehashes,tw_cache,tw_cache_time,tw_title,tw_time_below,tw_retweet,tw_reply,tw_user_display,tw_styles,tw_time_form,tw_follow_lang,tw_link_color,tw_text_color,tw_follow_count,tw_follow_button,tw_follow_name,tw_follow,tw_follow_move" />
		
		<input type="submit" class="button-primary" value="<?php _e('Save Settings', 'twitter-wings'); ?>" />
	
	</form>
</div>

<style>
	#twitter-wings-admin { font-size: 16px; line-height: 20px; }
	#twitter-wings-admin h1 { font-family: Georgia; font-style: italic; font-size: 24px; line-height: 35px; font-weight: normal; }
	#twitter-wings-admin label { width: 180px; float: left; color: #000; }
	#twitter-wings-admin .tw_input { float: left; width: 300px; }
	#twitter-wings-admin .short { width: 80px; }  
	#twitter-wings-admin .desc{ clear: left; display: block; font-size: 12px; color: #666; } 
	#twitter-wings-admin .formrow { padding: 7px; clear: both; border-bottom: 1px solid #e5e5e5; border-top: 2px solid #fff; }
	#twitter-wings-admin .first { border-top: none; } 
	#twitter-wings-admin .last { border-bottom: none; }  
	.button-primary { width: 100px; margin-top: 15px; height: 25px; }       
</style>