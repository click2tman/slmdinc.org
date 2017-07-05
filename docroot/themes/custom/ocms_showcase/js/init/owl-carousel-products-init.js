jQuery(document).ready(function($) {
  $(".mt-carousel-products").owlCarousel({
    items: 4,
    itemsDesktopSmall: [992,2],
    itemsTablet: [768,2],
    autoPlay: drupalSettings.ocms_showcase.owlCarouselProductsInit.owlProductsEffectTime,
    navigation: true,
    pagination: false,
    navigationText: false
  });
});
