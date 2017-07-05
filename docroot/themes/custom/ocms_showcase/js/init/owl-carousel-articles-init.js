jQuery(document).ready(function($) {
  $(".ocms-carousel-articles").owlCarousel({
    items: 2,
    itemsDesktop: [1200,2],
    itemsDesktopSmall: [992,2],
    itemsTablet: [768,1],
    autoPlay: drupalSettings.ocms_showcase.owlCarouselArticlesInit.owlArticlesEffectTime,
    navigation: true,
    pagination: true,
    navigationText: false
  });
});
