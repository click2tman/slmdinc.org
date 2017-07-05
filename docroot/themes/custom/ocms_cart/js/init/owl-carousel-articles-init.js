(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.mtOwlCarouselArticles = {
    attach: function (context, settings) {
      $(context).find('.ocms-carousel-articles').once('mtOwlCarouselArticlesInit').each(function() {
        $(this).owlCarousel({
          items: 2,
          responsive:{
            0:{
              items:1,
            },
            480:{
              items:1,
            },
            768:{
              items:2,
            },
            992:{
              items:2,
            },
            1200:{
              items:2,
            },
            1680:{
              items:2,
            }
          },
          autoplay: drupalSettings.ocms_cart.owlCarouselArticlesInit.owlArticlesAutoPlay,
          autoplayTimeout: drupalSettings.ocms_cart.owlCarouselArticlesInit.owlArticlesEffectTime,
          nav: true,
          dots: true,
          loop: true,
          navText: false
        });
      });
    }
  };
})(jQuery, Drupal, drupalSettings);
