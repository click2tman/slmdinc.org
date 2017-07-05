  /**
  * Add Javascript - Fixed Header
  */
jQuery(document).ready(function($) {
  var preHeaderHeight = $("#pre-header").outerHeight(),
  headerTopHeight = $("#header-top").outerHeight(),
  headerHeight = $("#header").outerHeight();
  $(window).load(function() {
    if(($(window).width() > 767)) {
      $("body").addClass("fixed-header-enabled");
    } else {
      $("body").removeClass("fixed-header-enabled");
    }
  });
  $(window).resize(function() {
    if(($(window).width() > 767)) {
      $("body").addClass("fixed-header-enabled");
    } else {
      $("body").removeClass("fixed-header-enabled");
    }
  });
  $(window).scroll(function() {
    if(($(this).scrollTop() > preHeaderHeight + headerTopHeight) && ($(window).width() > 767)) {
      $("body").addClass("onscroll");
      $("#header + div").css("paddingTop", (headerHeight)+"px");
      var adminHeight = $('body').css('paddingTop');
      $("#header").css("top", adminHeight);
    } else {
      $("body").removeClass("onscroll");
      $("#header").css("top", (0)+"px");
      $("#header + div").css("paddingTop", (0)+"px");
    }
  });
});
