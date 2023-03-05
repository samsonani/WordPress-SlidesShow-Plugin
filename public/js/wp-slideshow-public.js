(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
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

	$(window).load(function(){
		var slider = new Swiper('.gallery-slider', {
			slidesPerView: 1,
			centeredSlides: true,
			loop: true,
			loopedSlides: 6,
			navigation: {
				nextEl: '.swiper-button-next',
				prevEl: '.swiper-button-prev',
			},
		});
	
		var thumbs = new Swiper('.gallery-thumbs', {
			slidesPerView: 'auto',
			spaceBetween: 10,
			centeredSlides: true,
			loop: true,
			slideToClickedSlide: true,
		});
		slider.controller.control = thumbs;
		thumbs.controller.control = slider;
	});

})( jQuery );