jQuery(document).ready(function($) {
  $(".view-promoted-items .owl-carousel.team").owlCarousel({
    items: 4,
    itemsDesktopSmall: [992,2],
    itemsTablet: [768,2],
    autoPlay: drupalSettings.ocms_corporate.owlCarouselTeamInit.owlTeamEffectTime,
    navigation: true,
    pagination: false
  });
});
