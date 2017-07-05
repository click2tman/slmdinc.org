(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.mtowlCarouselPhotoGallery = {
    attach: function (context, settings) {
      $(context).find('.mt-carousel-photo-gallery').once('mtowlCarouselPhotoGalleryInit').each(function() {
        $(this).owlCarousel({
          items: 3,
          responsive:{
            0:{
              items:2,
            },
            480:{
              items:2,
            },
            768:{
              items:2,
            },
            992:{
              items:2,
            },
            1200:{
              items:3,
            },
            1680:{
              items:3,
            }
          },
          autoplay: drupalSettings.ocms_cart.owlCarouselPhotoGalleryInit.owlPhotoGalleryAutoPlay,
          autoplayTimeout: drupalSettings.ocms_cart.owlCarouselPhotoGalleryInit.owlPhotoGalleryEffectTime,
          nav: true,
          dots: false,
          loop: true,
          navText: false
        });
      });
    }
  };
})(jQuery, Drupal, drupalSettings);
