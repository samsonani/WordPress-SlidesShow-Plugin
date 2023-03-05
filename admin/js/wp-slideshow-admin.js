(function ($) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	var frame;
	$('#wpss-button').click(function (e) {
		e.preventDefault();
		var $this = $(this),
			$parent = $(this).parents(".wpss-input"),
			$input_name = $(this).attr("data-name");

		function clearField(){
			// #remove file nodes
			$parent.find(".wpss-gallery-attachments").empty();
		}

		frame = wp.media({
			title: 'Select',
			button: {
			  text: 'Select media'
			},
			multiple: true,  // Set to true to allow multiple files to be selected
			library : {
				type : 'image'
			}
		});

		//on close, if there is no select files, remove all the files already selected in your main frame
		frame.on('close',function() {
			var selection = frame.state().get('selection');
			if(!selection.length){
				clearField();
			}
		});


		frame.on( 'select',function() {
			var state = frame.state();
			var selection = state.get('selection');
			var imageArray = [];

			if ( ! selection ) return;

			clearField();

			// add selection to gallery
			selection.each(function(attachment) {

				//do what ever you like to use it
				// add html
				if($('.wpss-gallery-attachment[data-id="'+ attachment.attributes.id +'"]').length === 0) {					
					var html = [
					'<div class="wpss-gallery-attachment" data-id="' + attachment.attributes.id + '">',
						'<input type="hidden" value="' + attachment.attributes.id + '" name="' + $input_name + '">',
						'<div class="margin">',
							'<div class="thumbnail">',
								'<img src="' + attachment.attributes.sizes.thumbnail.url + '" alt="' + attachment.attributes.title + '">',
							'</div>',
						'</div>',
						'<div class="actions">',
							'<a class="wpss-icon -cancel dark wpss-gallery-remove" href="#" data-id="' + attachment.attributes.id + '" title="Remove"><span class="dashicons dashicons-remove"></span></a>',
						'</div>',
					'</div>'].join('');
					var $html = $(html);
						
					// append
					$parent.find(".wpss-gallery-attachments").append( $html );
				}

			});
		});

		//reset selection in popup, when open the popup
		frame.on('open',function() {
			var selection = frame.state().get('selection');
			var $content	=	frame.content.get().$el;
			var collection	=	frame.content.get().collection || null;
			
			var ids = [];
			$(".wpss-gallery-attachment").each(function(){
				var input_id = $(this).find('input[type="hidden"]').val();
				ids.push(input_id);
			});
			
			for (var i = 0; i < ids.length; i++){
				var attachment = wp.media.attachment(ids[i]);
				attachment.fetch();
				selection.add(attachment);
			}

			$content.find('.attachments-wrapper').addClass("test-modal");
		});

		//now open the popup
		frame.open();
	});

	/**
	 * Remove image from gallery
	 */
	$('body').on("click",".actions .wpss-gallery-remove",function (e) {
		e.preventDefault();
		let data_id = $(this).attr("data-id");
		$('.wpss-gallery-attachment[data-id="'+ data_id +'"]').remove();
	});

	/**
	 * Sotable gallery JS
	 */
	$(".wpss-gallery-attachments").sortable({
		items: ".wpss-gallery-attachment",
		forceHelperSize: true,
		forcePlaceholderSize: true,
		scroll: true,
		// connectWith: ".wpss-gallery-attachment",
		start: function (event, ui) {
			ui.placeholder.html( ui.item.html() );
			ui.placeholder.removeAttr('style');
		}
	});

})(jQuery);
