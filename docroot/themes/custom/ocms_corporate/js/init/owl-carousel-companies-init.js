jQuery(document).ready(function($) {
  $(".view-companies-carousel .owl-carousel.companies").owlCarousel({
    items: 5,
    itemsDesktopSmall: [992,3],
    itemsTablet: [768,3],
    autoPlay: drupalSettings.ocms_corporate.owlCarouselCompaniesInit.owlCompaniesEffectTime,
    navigation: true,
    pagination: false
  });
});
