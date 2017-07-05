jQuery(document).ready(function($) {
  $(".view-promoted-items .owl-carousel.posts").owlCarousel({
    items: 4,
    itemsDesktopSmall: [992,2],
    itemsTablet: [768,2],
    autoPlay: drupalSettings.ocms_corporate.owlCarouselPromotedPostsInit.owlPostsEffectTime,
    navigation: true,
    pagination: false
  });
});
