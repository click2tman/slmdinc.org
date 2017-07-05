+function ($) {
  'use strict';

	//  Set variables 
	var accordHeadings = $('.content').find('.ocms-accordion-heading'), 
			accordBodies = $('.content').find('.ocms-accordion-body');

	// Add id to the parent element
	$('.content').attr( "id", "article-accordion-content");

	function classesAndIds() {
		if (window.innerWidth <= 768) { 
			// add classes and ids in mobile view
			$(accordBodies).addClass('collapse');
			$(accordHeadings).addClass('collapsed');
			$(accordBodies).each(function(item, element){
				var el = $(element).attr({"id"	: "collapse-" + (item+1)});
			});
			$(accordHeadings).each(function(item, element) {
				var el = $(element).attr({
					"data-toggle"   : "collapse",
					"data-parent"   : "#article-accordion-content",
					"href" 		      : "#collapse-" + (item +1)
				});
			});
			} else {
			// remove classes and ids in desktop view
			$(accordBodies).removeAttr('id href style').removeClass('collapse');
			$(accordHeadings).removeAttr('data-toggle data-parent href').removeClass('collapsed');
		}
	};

	$(window).resize( function() {
		classesAndIds();
	});

	classesAndIds();
	
}(jQuery);
