<?php
/*
Plugin Name: Twitter Wings
Plugin URI: https://github.com/joepahl/twitter-wings
Version: 1.0
Description: Display tweets from one or more users. Output the display URL for links, and hide the t.co URL. Filter tweets by hashtags and/or hide hashtags altogether. Built in caching.
Author: Joe Pahl
Author URI: http://joepahl.is
Text Domain: twitter-wings
*/

/*  Copyright 2011  Joe Pahl  (emailme@joepahl.is)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* options page */
$options_page = get_option('siteurl') . '/wp-admin/admin.php?page=twitter-wings/options.php';

/* Adds our admin options under "Options" */
function tw_options_page() {
	add_options_page('Twitter Wings', 'Twitter Wings', 10, 'twitter-wings/options.php');
}

function TwitterWings(){
	new TwitterWingsStart;
}

class TwitterWingsStart {
			
	private $users;
	private $no_of_statuses;
	private $hashes;
	private $avatars;
	private $cache_on;
	
	private $T_URL 	  = "http://api.twitter.com/1/statuses/user_timeline";
	private $T_FORMAT = ".xml";
	private $T_CACHE;
	
	function __construct(){
		$sitename = strtolower(str_replace(" ","-",get_bloginfo('name')));
		$force = $_GET['tw_force']; // force source from url
		
		$site_dir = dirname(__FILE__) . '/cache/' . $sitename;
		
		if (!is_dir($site_dir)) {
			mkdir($site_dir, 0755);
		}
		
		$this->T_CACHE = dirname(__FILE__) . '/cache/' . $sitename . '/cache.ch';
		
		$cache_on = get_option('tw_cache');
		$this->cache_on = $cache_on;
		
		$users = get_option('tw_usernames');
		$this->users = explode(",",$users);
		
		$this->no_of_statuses = is_numeric(get_option('tw_number')) ? get_option('tw_number') : 15;
		
		$hashes = get_option('tw_hashes');
		$this->hashes = explode(",",$hashes);

		$data = $this->tw_getData($force);
		
		$twitter_wrap = "\n<div id='twitter-wings'>\n\t";
		$twitter_wrap .= "<h1 class='widget-title tw-title'>" . get_option('tw_title')."</h1>\n\t";
	
		$twitter_body = $this->tw_printData($data);
		$twitter_wrap .= $twitter_body;
		$twitter_wrap .= "\n</div>\n";
		
		if ($twitter_body != "<div class='tw-body'></div>") {
			echo $twitter_wrap;
		}
		
	}
		
	/**
	 * Return data to display
	 * Calculate source, Api or Cache
	 * 
	 */
	private function tw_getData($force){
		
		if($force == 'CACHE'){
			if(file_exists($this->T_CACHE)){			
				$data = unserialize(file_get_contents($this->T_CACHE));
				return $data;
			} else {
				//_e('Sorry, we\'re not able to show data from Twitter at this time.', 'twitter-wings');
				return;
			}
		}
		
		if($force == 'API'){
			$data = $this->tw_getApiData();
			return $data;
		}
		
		if(file_exists($this->T_CACHE) && $this->cache_on) {
			$cache_time = filemtime($this->T_CACHE);
			if(time()-$cache_time > 60){
				$data = $this->tw_getApiData();
			} else {
				$data = unserialize(file_get_contents($this->T_CACHE));
			}
		} else {
			$data = $this->tw_getApiData();
		}
		return $data;
	}
	
	/**
	 * 
	 * Create array from Twitter API response
	 * 
	 */	
	public function tw_getApiData(){
		
		$api_query = '&include_entities=1';
		$api_query .= (get_option('tw_reply') == '') ? '&exclude_replies=1' : '';
		$api_query .= (get_option('tw_retweet') == '') ? '&include_rts=1' : '';
		$api_query .= ($this->no_of_statuses > 20) ? "&count={$this->no_of_statuses}" : '';
		
		foreach ($this->users as $key=>$name) {
			
			if (!$name) {
				echo sprintf(__('Twitter Wings was not configured correctly. Add the username(s) you would like to display on the %soptions page%s.', 'twitter-wings'), "<a href='" . get_option('siteurl') . "/wp-admin/options-general.php?page=twitter-wings/options.php'>", "</a>");
				break;
			}
												
			$url = rawurlencode($this->T_URL . $this->T_FORMAT . '/?screen_name=' . $name . $api_query);
						
			$xml = @simplexml_load_file($url);
			 							
			/* if there is error in Twitter response force data from Cache */
			if (!$xml) {
				$data = $this->tw_getData('CACHE');
				return $data;
			}
						
			foreach ($xml as $x) {
				
				$retweet = ($x->retweeted_status) ? true : false;
				
				// CONTENT (IF IT'S A RETWEET GET THE ORIGINAL)
				if ($retweet)
					$tmp['text'] = 'RT @' . (string)$x->retweeted_status->user->screen_name . ': ' . (string)$x->retweeted_status->text;
				else
					$tmp['text'] = (string)$x->text;
				
				// MENTIONS (IF IT'S A RETWEET GET THE ORIGINAL)
				$tmp['mentions'] = '';
				if ($retweet && $x->retweeted_status->entities->user_mentions->user_mention) {
					foreach ($x->retweeted_status->entities->user_mentions->user_mention as $mention) {
						$m['screen_name'] = (string)$mention->screen_name;
						$tmp['mentions'][] = $m;  // ah sss push it
					}
					// add the user you are retweeting to the array!
					$m['screen_name'] = (string)$x->retweeted_status->user->screen_name;
					$tmp['mentions'][] = $m;  // ah sss push it
				
				} elseif ($x->entities->user_mentions->user_mention) {
					foreach ($x->entities->user_mentions->user_mention as $mention) {
						$m['screen_name'] = (string)$mention->screen_name;
						$tmp['mentions'][] = $m; // ah sss push it
					}
				}
								
				// URLS (IF IT'S A RETWEET GET THE ORIGINAL)
				$tmp['urls'] = '';
				if ($retweet && $x->retweeted_status->entities->urls->url) {
					foreach ($x->retweeted_status->entities->urls->url as $url) { 
						$l['url'] = (string)$url->url;
						$l['display_url'] = (string)$url->display_url;
						$tmp['urls'][] = $l; // push it real good
					}
				} elseif ($x->entities->urls->url) {
					foreach ($x->entities->urls->url as $url) { 
						$l['url'] = (string)$url->url;
						$l['display_url'] = (string)$url->display_url;
						$tmp['urls'][] = $l; // push it real good
					}
				}
				
				// HASHTAGS (IF IT'S A RETWEET GET THE ORIGINAL)
				$tmp['hashtags'] = '';
				if ($retweet && $x->retweeted_status->entities->hashtags->hashtag) {
					foreach ($x->retweeted_status->entities->hashtags->hashtag as $hashtag) {
						$h['hashtag'] = (string)$hashtag->text;
						$tmp['hashtags'][] = $h; // ah sss push it
					}
				} elseif ($x->entities->hashtags->hashtag) {
					foreach ($x->entities->hashtags->hashtag as $hashtag) {
						$h['hashtag'] = (string)$hashtag->text;
						$tmp['hashtags'][] = $h; // ah sss push it
					}
				}
				
				$tmp['time'] 		 = (string)$x->created_at;
				$tmp['timestamp']	 = (string)strtotime($x->created_at);
				$tmp['name'] 		 = (string)$x->user->name;
				$tmp['username']	 = (string)$x->user->screen_name;
				$tmp['avatar']		 = (string)$x->user->profile_image_url;
				$tmp['permalink']	 = (string)'http://twitter.com/' . $x->user->screen_name . '/status/' . $x->id;
				$sts[] = $tmp;

			}
		}
		
		/* sort statuses array by timestamp */
		$tmp = $sts;
		foreach ($tmp as $key=>$row) {
			$text[$key] = $row['timestamp'];
		}
	
		array_multisort($text,SORT_DESC,$tmp);
	
		$sts = $tmp;
		/* end sorting */		
			
		/* put data in file for latter use : cache */	
		$cache = fopen($this->T_CACHE, 'w');
		fwrite($cache, serialize($sts));
		fclose($cache);
		
		return $sts;	
	}
	

	/**
	 * Print Twitter data
	 * 
	 * 
	 */
	private function tw_printData($sts) {
		
		$i = 0;
		$twitter_body = "<div class='tw-body'>";
		if (is_array($sts)) {			
			foreach ($sts as $key=>$val) {
								
				if($this->tw_checkHashes($val['text'], $val['hashtags'])) continue;
				
				$img = (get_option('tw_photos') != '') ? "<div class='tw-avatar'><a href='http://www.twitter.com/{$val['username']}' title='@{$val['username']} on Twitter'><img src='{$val['avatar']}' height=48 width=48 /></a></div>" : '';
				
				$hd_o = '';
				$hd_c = '';
				
				if (get_option('tw_user_titles') == '') {
					$screenname = '';
				} else { 
					$display_name = '';
					if (get_option('tw_user_display') != '')
						$display_name = "<span class='tw-display-name'> {$val['name']}</span>";
					$screenname = "<p class='tw-name'><a href='http://www.twitter.com/{$val['username']}' title='@{$val['username']} on Twitter'>{$val['username']}</a>$display_name</p>";
					$hd_o = "<header>";
					$hd_c = "</header>";
				}
								
				$text = $this->tw_parseStatus($val['text'], $val['urls'], $val['mentions'], $val['username'], $val['hashtags']);
				
				$username = strtolower($val['username']);
				
				$timestamp = "<p class='tw-time'><time pubdate datetime='" . strftime('%FT%TZ', $val['timestamp']) . "'><a href='{$val['permalink']}' title='" . __('Permalink', 'twitter-wings') . "'>{$this->tw_showTime($val['timestamp'])}</a></time></p>";

				if (get_option('tw_time_below') == '') {
					$time_above = $timestamp;
					$time_below = '';
					$hd_o = "<header>";
					$hd_c = "</header>";
				} else { 
					$time_above = '';
					$time_below = '<footer>' . $timestamp . '</footer>';
				}
				
				$twitter_body .= "\n\t\t<article class='tw-status tw-$username'>{$img}<div class='tw-content'>{$hd_o}{$screenname}{$time_above}{$hd_c}<p class='tw-text'>$text</p>$time_below</div></article>\n\t";
				$i++;
				if($i == $this->no_of_statuses) break;
			}
		}
		$twitter_body .= "</div>";
		return $twitter_body;
	}
	
	/**
	 * Create 'human readable' date format from timestamp
	 * 
	 * 
	 */
	private function tw_showTime($ts){
		$c = time() - $ts;
		$nd = date('g:i A M d\, Y',$ts);
		
		if ($c < 60) return $c . ' seconds ago';
		elseif ($c < 3600) return (int)($c/60) . ' minutes ago';
		elseif ($c < 3600*24) return (int)($c/3600) . ' hours ago';
		else return $nd;
	}
	
	/**
	 * 
	 * Adding links, screen names, keywords 
	 * Remove unwanted hashes
	 * 
	 */	
	private function tw_parseStatus($text, $urls, $mentions, $username, $hashtags) {

		// add url links
		if(get_option('tw_https') != '' && $urls) {
			
			// loop through URLS (add the pretty links)
			foreach($urls as $url_array) {			
				extract($url_array);
				// fallback for links sans-display_url
				if (!$display_url)							
					$display_url = $url;
				$text = preg_replace("@{$url}([^A-Za-z0-9\"'><]|\s|$)@iu", "<a href='{$url}' class='tw-url'><span>{$display_url}<span></a>$1", $text); // using @ to delimit, as / shows up in $url
			}
		}	
			
		// add mention links
		if(get_option('tw_screennames') != '' && $mentions) {
			
			// loop through MENTIONS
			foreach($mentions as $mention_array) {
				extract($mention_array);
				$text =  preg_replace("/@{$screen_name}\b/iuU", "<a href='http://twitter.com/{$screen_name}' class='tw-mention'>@<span>{$screen_name}</span></a>", $text);
			}
			
			// mentioning yourself is lame, but here ya go (case-insensitive b/c we are dealing with primates)
			if (stripos($text, "@{$username}")) {
				$text = preg_replace("/@{$username}\b/iuU", "<a href='http://twitter.com/{$username}' class='tw-mention'>@<span>{$username}</span></a>", $text);
			}
		}
		
		// remove hashes
		if(get_option('tw_removehashes') != '' && $hashtags) {
			foreach($hashtags as $hash_array) {
				extract($hash_array);
				$text = preg_replace("/#{$hashtag}\b/iU", "", $text);
			}
		}
	
		// hashtag links
		if(get_option('tw_chashes') != '' && $hashtags) {
			// loop through HASHTAGS
			foreach($hashtags as $hash_array) {
				extract($hash_array);
				$text =  preg_replace("/#{$hashtag}\b/iU", "<a href='http://twitter.com/search?q=%23{$hashtag}' class='tw-hashtag'>#<span>{$hashtag}</span></a>", $text);
			}
		}
			
		return $text;
	}

	/**
	 * 
	 * Does status have the hashes that we want to display?
	 * "No" means "Yes" and "Yes" means "No" (don't let the TRUE/FALSE confuse you)
	 * 
	 */
	private function tw_checkHashes($text, $hashtags){

		if(get_option('tw_hashes') != '') {
			if ($hashtags) {
				foreach($this->hashes as $val) {
					$strip = array(' ', '#');
					$val = str_replace($strip, '', $val);
										
					foreach ($hashtags as $hash_array) {
						extract($hash_array);
						if (strtolower($hashtag) == strtolower($val)) return false;
					}
					return true;
				}
				return true;
			}
			return true;
		} else {
			return false;
		}
	}
}

class TwitterWings {
	function control(){
		echo sprintf(__('Configure this widget on %soptions page%s.', 'twitter-wings'), "<a href='" . get_option('siteurl') . "/wp-admin/options-general.php?page=twitter-wings/options.php'>", "</a>");
	}
	function widget($args){
		echo $args['before_widget'];
		new TwitterWingsStart();
		echo $args['after_widget'];
	}
	function register(){
		register_sidebar_widget('Twitter Wings', array('TwitterWings', 'widget'));
		register_widget_control('Twitter Wings', array('TwitterWings', 'control'));
	}	
}

// ENQUEUE STYLES
function tw_styles() {
	$css_path = plugins_url('css/tw_styles.css', __FILE__);
	wp_enqueue_style('tw-stylesheet', $css_path);
}

// Install and Uninstall
register_activation_hook(__FILE__, 'tw_install');
register_deactivation_hook(__FILE__, 'tw_uninstall');

// Default Settings
// When plugin is activated, update version, and set any new settings to default
function tw_install() {
	add_option('tw_active_version', '1.0');
	add_option('tw_usernames', 'joepahl');
	add_option('tw_hashes', '');
	add_option('tw_title', 'Twitter');
	add_option('tw_number',	'15');
	add_option('tw_photos', '');
	add_option('tw_user_titles', '1');
	add_option('tw_user_display', '');
	add_option('tw_time_below', '');
	add_option('tw_reply', '');
	add_option('tw_retweet', '');
	add_option('tw_https', '1');
	add_option('tw_screennames', '1');
	add_option('tw_chashes', '1');
	add_option('tw_removehashes', '');
	add_option('tw_cache', '1');
	add_option('tw_styles', '');
}

// Delete settings on when uninstalled
function tw_uninstall() {
	delete_option('tw_active_version');
	delete_option('tw_cache');
}

function tw_delete_cache() {
	$sitename = strtolower(str_replace(" ","-",get_bloginfo('name')));
	$cache = dirname(__FILE__) . '/cache/' . $sitename . '/cache.ch';
	unlink($cache);
}

if (get_option('tw_styles') == '')
	add_action('wp_print_styles', 'tw_styles');

add_action('update_option_tw_usernames', 'tw_delete_cache');	
add_action('admin_menu','tw_options_page');
add_action('widgets_init', array('TwitterWings', 'register'));