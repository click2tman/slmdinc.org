jQuery(document).ready(function($) {

  $(window).load(function () {
    if ($(".post-progress").length>0){
      var s = $(window).scrollTop(),
      c = $(window).height(),
      d = $(".node--main-content").outerHeight(),
      g = $(".node--main-content").offset().top;
      var scrollPercent = (s / (d+g-c)) * 100;
      scrollPercent = Math.round(scrollPercent);
      if (c >= (d+g)) { scrollPercent = 100; } else if (scrollPercent < 0) { scrollPercent = 0; } else if (scrollPercent > 100) { scrollPercent = 100; }
      $(".post-progressbar").css("width", scrollPercent + "%");
      $(".post-progress-value").html(scrollPercent + "%");
    }
  });

  $(window).scroll(function () {
    if ($(".post-progress").length>0){
      var s = $(window).scrollTop(),
      c = $(window).height(),
      d = $(".node--main-content").outerHeight(true),
      g = $(".node--main-content").offset().top;
      var scrollPercent = (s / (d+g-c)) * 100;
      scrollPercent = Math.round(scrollPercent);
      if (c >= (d+g)) { scrollPercent = 100; }  else if (scrollPercent < 0) { scrollPercent = 0; } else if (scrollPercent > 100) { scrollPercent = 100; }
      $(".post-progressbar").css("width", scrollPercent + "%");
      $(".post-progress-value").html(scrollPercent + "%");
    }
  });

});
