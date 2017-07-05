jQuery(document).ready(function($) {
  $( "#search-area #block-ocms-navigation-search .form-search" ).focusin(function() {
    $("#search-area").addClass("opened");
  });
  $( "#search-area #block-ocms-navigation-search .form-search" ).focusout(function() {
    $("#search-area").removeClass("opened");
  });
});
