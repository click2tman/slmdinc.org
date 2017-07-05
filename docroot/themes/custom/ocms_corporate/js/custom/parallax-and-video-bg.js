jQuery(document).ready(function($) {
  var color = $("#highlighted-bottom").css("background-color").replace(")", "," + drupalSettings.ocms_corporate.ParallaxAndVideoBg.ParallaxAndVideoBgOpacity + ")").replace("rgb", "rgba");
  $("#highlighted-bottom-transparent-bg").css("background-color", color);
});