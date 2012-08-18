jQuery(document).ready(function() {
	console.log('hello Twitter Wings');
	
	// CACHE
	jQuery('p.cache-time').hide();

	if (jQuery('p.cache input').prop("checked")) {
		jQuery('p.cache-time').show();
	}
	
	jQuery('p.cache input').change(function() {
		jQuery('p.cache-time').toggle('fast');
	});
	
	// HASHTAG FILTER
	jQuery('p.hash-terms').hide();

	if (jQuery('p.hashtag input').prop("checked")) {
		jQuery('p.hash-terms').show();
	}
	
	jQuery('p.hashtag input').change(function() {
		if (jQuery('p.hashtag input').prop("checked") == false) {
			jQuery('p.hash-terms input').val('');
		}
		jQuery('p.hash-terms').toggle('fast');
	});
	
	// DISPLAY NAME
	jQuery('p.display-name').hide();

	if (jQuery('p.username input').prop("checked")) {
		jQuery('p.display-name').show();
	}
	
	jQuery('p.username input').change(function() {
		jQuery('p.display-name').toggle('fast');
	});
	
	// FOLLOW BUTTON
	jQuery('div.follow-block').hide();

	if (jQuery('p.add-follow input').prop("checked")) {
		jQuery('div.follow-block').show();
	}
	
	jQuery('p.add-follow input').change(function() {
		jQuery('div.follow-block').toggle('fast');
	});

}); /* End Document Ready */