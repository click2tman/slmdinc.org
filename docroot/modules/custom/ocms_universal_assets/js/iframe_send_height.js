/**
 * @file
 * Send parent of iframe the updated height.
 */

(function ($) {

  Drupal.behaviors.iframeSendHeight = {
    attach:function (context, settings) {

      var pymChild = new pym.Child();

      // Delay update height to resolve cut-off footer.
      setTimeout(function() {
        pymChild.sendHeight();
      }, 400);

      // Update child height on click for dom updates like expanded content.
      $(document, context).click(function() {
        setTimeout(function() {
          pymChild.sendHeight();
        }, 400);
      });

    }
  };

})(jQuery);
