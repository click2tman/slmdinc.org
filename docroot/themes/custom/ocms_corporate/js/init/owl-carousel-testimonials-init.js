jQuery(document).ready(function($) {
  $(".view-testimonials-carousel .owl-carousel.testimonials").owlCarousel({
    items: 4,
    itemsDesktopSmall: [992,2],
    itemsTablet: [768,2],
    autoPlay: drupalSettings.ocms_corporate.owlCarouselTestimonialsInit.owlTestimonialsEffectTime,
    navigation: true,
    pagination: false
  });
});
