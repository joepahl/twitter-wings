=== Twitter Wings ===
Contributors: joepahl, bsdeluxe
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5RR6SHW9UBJSA
Tags: twitter, sidebar, widget, HTML5, plugin, links
Requires at least: 3.0
Tested up to: 3.2
Stable tag: trunk

An easy to configure Twitter Plugin with Pretty URLs. 

== Description ==

With Twitter Wings you can:

* display your latest tweets
* replace t.co links with display URLs (pretty links)
* add/configure a Twitter Follow Button
* include tweets from one or multiple accounts
* cache tweets for faster page load
* customize the timestamp
* show/hide username
* show/hide display name
* filter tweets by hashtags
* strip hashtags from tweets
* show/hide retweets
* show/hide replies
* HTML5 semantic markup
* implement as a widget or by using `TwitterWings()` template function

More information about Twitter Wings is available at https://github.com/joepahl/twitter-wings

== Installation ==

1. Upload `twitter-wings` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Drag the Twitter Wings widget into your sidebar
4. Set options in admin panel

-or-

1. Upload `twitter-wings` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add `<?php TwitterWings(); ?>` to your template.
4. Set options in admin panel


== Screenshots ==

1. Twitter Wings in action, sample configurations
2. Twitter Wings options page
3. Sidebar widget

== Frequently Asked Questions ==

= My tweets are not displaying. Where are they? =

There are a couple of reasons that this may happen. One is that Twitter's API rate limit has been exhausted. The rate limit is refreshed hourly. If you are on a shared server this can be caused by someone else's site.

Another possibility is that your Twitter account is protected. Private accounts cannot be accessed via the public API.

A future version of the plugin will include an option to register a private API key. This will prevent both of these issues.

== Changelog ==

= 1.2.1 =
* fix jQuery conflict that broke WordPress admin menus, making them undraggable
* add FAQ section to readme file

= 1.2 =
* added support for local timezone
* local timezone determined by user's timezone setting in WordPress admin

= 1.1 =
* added alt attribute to avatar image
* replaced strftime function, with generic date function

== Upgrade Notice ==

= 1.2.1 =
This update fixes a jQuery conflict that was rendering WordPress admin menus undraggable.

= 1.2 =
This update adds local timezone support. Local timezone is determined by the WordPress admin setting. The datetime attribute remains in GMT.

= 1.1 =
This update adds an alt attribute to the avatar image so the plugin validates in HTML5. The strftime function was causing issues for some users, and has been replace with the date function.