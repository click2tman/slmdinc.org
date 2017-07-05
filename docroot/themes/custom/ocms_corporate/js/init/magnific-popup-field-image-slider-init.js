jQuery(document).ready(function($) {
  $(window).load(function() {
    $("li:not(.clone) .image-popup").magnificPopup({
      type:"image",
      removalDelay: 300,
      mainClass: "mfp-fade",
      gallery: {
        enabled: true, // set to true to enable gallery
      },
      image: {
        titleSrc: function(item) {
          return item.el.children()[0].title || '';
        }
      }
    });
    $(".one-value .image-popup").magnificPopup({
      type:"image",
      removalDelay: 300,
      mainClass: "mfp-fade",
      gallery: {
        enabled: true, // set to true to enable gallery
      },
      image: {
        titleSrc: function(item) {
          return item.el.children()[0].title || '';
        }
      }
    });
  });
});