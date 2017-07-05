jQuery(document).ready(function($) {
  $("body").addClass("video-bg-active");
  $(".video-bg-active #highlighted-bottom").vide({
    mp4: drupalSettings.ocms_corporate.VideoBackgroundInit.PathToVideo_mp4,
    webm: drupalSettings.ocms_corporate.VideoBackgroundInit.PathToVideo_webm,
    poster: drupalSettings.ocms_corporate.VideoBackgroundInit.pathToVideo_jpg
  },{
    posterType: 'jpg',
    className: 'video-container'
  });
});