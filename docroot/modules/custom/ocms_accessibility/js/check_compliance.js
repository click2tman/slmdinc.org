/**
 * @file
 * Force ckeditor plugin a11ychecker to be called on form submit.
 */

(function ($, Drupal, proxyAlert) {
  // Number of checker submissions via main form submit.
  var submit_form_checking = 0;

  // Alert proxy to suppress no-issue alert on submit.
  window.alert = function() { 
    var a11ychecker_valid_msg = 'does not contain any accessibility issues';

    // Suppress alert & decrement # of editors being checked.
    if (submit_form_checking > 0 && arguments[0].indexOf(a11ychecker_valid_msg)) { 
      submit_form_checking -= 1;
      return; 
    }
    return proxyAlert.apply(this, arguments); 
  };

  Drupal.behaviors.accessibilityFormValidation = {
    attach:function (context, settings) {

      // Submit button.
      $('.submit-with-accessibility-checker', context).once("form_submit").each(function () {
        $(this).click(function(e) {
          if (!checkCompliant(this)) {
            e.preventDefault();
          }
        });
      });

      function checkCompliant(button) {
        // No checker button found or manually checked so continue submit.
        var manually_checked = $("#edit-field-confirm-508-value").is(':checked');
        var no_checker_button = $('.cke_button__a11ychecker').length == 0;
        if (manually_checked || no_checker_button) {
          return true;
        }

        // Run a11ychecker.
        submit_form_checking = $('.cke_button__a11ychecker').length;
        if (navigator.appVersion.indexOf('Edge') > -1) {
          $('.cke_button__a11ychecker').mouseup();
        } else {
          $('.cke_button__a11ychecker').click();
        }

        // Check if balloon dialog with warnings appear, otherwise continue.
        setTimeout(function() {
          if ($('.cke_balloon').prop('style')['display'] !== 'none') {
            return false;
          }
          else {
            $(button).unbind(event).click();
          }
        }, 2000);
      }
    }
  };

})(jQuery, Drupal, window.alert);
