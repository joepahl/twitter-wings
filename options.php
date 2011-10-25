<?php
$location = $options_page; // Form Action URI
$ch = '';
?>

<div id="twitter-wings">
	<h1>Twitter Wings</h1>

	
	<form method="post" action="options.php">

		<?php wp_nonce_field('update-options'); ?>

		<div class="formrow first">
			<label for="usernames">Twitter Username(s):</label>
			<input type="text" id="usernames" name="tw_usernames" class="tw_input" value="<?php echo get_option('tw_usernames'); ?>" />
			<span class="desc">List of Twitter accounts separated with ",". e.g. <i>joepahl,bsdeluxe,dylanized</i></span>
		</div>
		
		<div class="formrow">		
			<label for="hashes">Hashes:</label>
			<input type="text" id="hashes" name="tw_hashes" class="tw_input" value="<?php echo get_option('tw_hashes'); ?>" />
			<span class="desc">List of hashes that post which you want to be displayed contains separated with ",". e.g. <i>#STL,#fh,#AEA</i></span>								
		</div>
		
		<div class="formrow">		
			<label for="hashes">Header title:</label>
			<input type="text" id="hashes" name="tw_title" class="tw_input" value="<?php echo (get_option('tw_title')) ? get_option('tw_title') : 'Twitter'; ?>" />
			<span class="desc">Title in header before Twitter posts. (default: Twitter)</span>								
		</div>		

		<div class="formrow">		
			<label for="number">Number of Posts:</label>
			<input type="text" id="number" name="tw_number" class="tw_input" value="<?php echo get_option('tw_number'); ?>" />
			<span class="desc">Total number of posts. (default: 15)</span>								
		</div>	
		
		<div class="formrow">		
			<label>Show User's Photo</label>
			<?php if(get_option('tw_photos') != ""){ $ch = ' checked'; } else { $ch = ''; } ?>
			<input type="checkbox" id="photos" name="tw_photos"<?php echo $ch ?> />
			<span class="desc">This option will show user photo with every message. (default: on)</span>					
		</div>
		
		<div class="formrow">		
			<label>Show User's Name</label>
			<?php if(get_option('tw_user_titles') != ""){ $ch = ' checked'; } else { $ch = ''; } ?>
			<input type="checkbox" id="titles" name="tw_user_titles"<?php echo $ch ?> />
			<span class="desc">This option will show user name with every message. (default: on)</span>					
		</div>
		
		<div class="formrow">		
			<label>Show Display Name</label>
			<?php if(get_option('tw_user_display') != ""){ $ch = ' checked'; } else { $ch = ''; } ?>
			<input type="checkbox" id="user_display" name="tw_user_display"<?php echo $ch ?> />
			<span class="desc">This option will show user full name next to their username. Show User's Name must be active. (default: off)</span>					
		</div>
		
		<div class="formrow">		
			<label>Timestamp Below Text</label>
			<?php if(get_option('tw_time_below') != ""){ $ch = ' checked'; } else { $ch = ''; } ?>
			<input type="checkbox" id="time_below" name="tw_time_below"<?php echo $ch ?> />
			<span class="desc">This option move the timestamp, positioning it below the tweet. (default: off)</span>					
		</div>
		
		<div class="formrow">		
			<label>Show Replies</label>
			<?php if(get_option('tw_reply') != ""){ $ch = ' checked'; } else { $ch = ''; } ?>
			<input type="checkbox" id="reply" name="tw_reply"<?php echo $ch ?> />
			<span class="desc">Include replies. (default: off)</span>				
		</div>
		
		<div class="formrow">		
			<label>Hide Retweets</label>
			<?php if(get_option('tw_retweet') != ""){ $ch = ' checked'; } else { $ch = ''; } ?>
			<input type="checkbox" id="retweet" name="tw_retweet"<?php echo $ch ?> />
			<span class="desc">Hide native retweets. (default: off)</span>				
		</div>
		
		<div class="formrow">		
			<label>Link http://</label>
			<?php if(get_option('tw_https') != ""){ $ch = ' checked'; } else { $ch = ''; } ?>
			<input type="checkbox" id="https" name="tw_https"<?php echo $ch ?> />
			<span class="desc">Create hyperlinks from URLs. (default: on)</span>				
		</div>
		
		<div class="formrow">		
			<label>Link @screennames</label>
			<?php if(get_option('tw_screennames') != ""){ $ch = ' checked'; } else { $ch = ''; } ?>
			<input type="checkbox" id="screennames" name="tw_screennames"<?php echo $ch ?> />
			<span class="desc">Create links from screennames in messages. (default: on)</span>				
		</div>
		
		<div class="formrow">		
			<label>Link #hashes</label>
			<?php if(get_option('tw_chashes') != ""){ $ch = ' checked'; } else { $ch = ''; } ?>
			<input type="checkbox" id="chashes" name="tw_chashes"<?php echo $ch ?> />
			<span class="desc">Create links from hashes in messages. (default: on)</span>				
		</div>
		
		<div class="formrow">		
			<label>Remove #hashes</label>
			<?php if(get_option('tw_removehashes') != ""){ $ch = ' checked'; } else { $ch = ''; } ?>
			<input type="checkbox" id="removehashes" name="tw_removehashes"<?php echo $ch ?> />
			<span class="desc">Remove hashes from list in messages. (default: off)</span>				
		</div>
				
		<div class="formrow">		
			<label>Use Cache</label>
			<?php if(get_option('tw_cache') != ""){ $ch = ' checked'; } else { $ch = ''; } ?>
			<input type="checkbox" id="cache" name="tw_cache"<?php echo $ch ?> />
			<span class="desc">Using cache will improve your page load. Data will be saved in cache every minute. (default: on)</span>				
		</div>
		
		<div class="formrow last">		
			<label>Remove Stylesheet</label>
			<?php if(get_option('tw_styles') != ""){ $ch = ' checked'; } else { $ch = ''; } ?>
			<input type="checkbox" id="styles" name="tw_styles"<?php echo $ch ?> />
			<span class="desc">Remove default css. I'm using my own stylesheet. Don't cramp my style. (default: off)</span>				
		</div>

		<input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="tw_usernames,tw_hashes,tw_number,tw_photos,tw_user_titles,tw_screennames,tw_https,tw_chashes,tw_removehashes,tw_cache,tw_title,tw_time_below,tw_retweet,tw_reply,tw_user_display,tw_styles" />
		
		<input type="submit" class="button-primary" value="Save Settings" />
	
	</form>
</div>

<style>
	#twitter-wings { font-size: 16px; line-height: 20px; }
	#twitter-wings h1 { font-family: Georgia; font-style: italic; font-size: 24px; line-height: 35px; font-weight: normal; }
	#twitter-wings label { width: 180px; float: left; color: #000; }
	#twitter-wings .tw_input { float: left; width: 300px; }  
	#twitter-wings .desc{ clear: left; display: block; font-size: 12px; color: #666; } 
	#twitter-wings .formrow { padding: 7px; clear: both; border-bottom: 1px solid #e5e5e5; border-top: 2px solid #fff; }
	#twitter-wings .first { border-top: none; } 
	#twitter-wings .last { border-bottom: none; }  
	.button-primary { width: 100px; margin-top: 15px; height: 25px; }       
</style>