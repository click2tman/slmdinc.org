jQuery(document).ready(function($) {
  $("body").addClass("video-bg-active");
  $(".video-bg-active .media-background").vide({
    mp4: drupalSettings.ocms_showcase.VideoBackgroundInit.PathToVideo_mp4,
    webm: drupalSettings.ocms_showcase.VideoBackgroundInit.PathToVideo_webm,
    poster: drupalSettings.ocms_showcase.VideoBackgroundInit.pathToVideo_jpg
  },{
    posterType: 'jpg',
    className: 'video-container'
  });
});