(function($) {

	'use strict';

	$(function() {

		$('.wsf-admin-bar-debug-console').on('click', function(e) {

			// Prevent default
			e.preventDefault();

			// Get href
			var href = $('a', $(this)).attr('href');

			// Remove #
			var helper_debug = href.substring(1);

			// Check debug console state
			if(['off', 'administrator', 'on'].indexOf(helper_debug) === -1) { return; }

			// Make AJAX request
			$.ajax({

				url: ws_form_toolbar.api_url + helper_debug + '/',
				type: 'POST',
				beforeSend: function(xhr) {

					xhr.setRequestHeader('X-WP-Nonce', ws_form_toolbar.x_wp_nonce);
				},
				complete: function(data){

					location.reload();
				}
			});
		});
	});

})(jQuery);