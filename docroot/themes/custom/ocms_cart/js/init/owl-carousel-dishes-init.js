(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.mtowlCarouselDishes = {
    attach: function (context, settings) {
      $(context).find('.mt-carousel-dishes').once('mtowlCarouselDishesInit').each(function() {
        $(this).owlCarousel({
          items: 2,
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
          autoplay: drupalSettings.ocms_cart.owlCarouselDishesInit.owlDishesAutoPlay,
          autoplayTimeout: drupalSettings.ocms_cart.owlCarouselDishesInit.owlDishesEffectTime,
          nav: true,
          dots: false,
          loop: true,
          navText: false
        });
      });
    }
  };
})(jQuery, Drupal, drupalSettings);
