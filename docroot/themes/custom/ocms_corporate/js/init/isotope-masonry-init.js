jQuery(document).ready(function($) {

  //Masonry Layout
  $(".grid-masonry-container").fadeIn("slow");
  $(".grid-masonry-container").imagesLoaded(function() {
    $(".grid-masonry-container").isotope({
      itemSelector: ".masonry-grid-item",
      layoutMode: "masonry"
    });
    $(".grid-masonry-container").isotope("layout");
  });

  //Masonry Layout Style 2
  $(".grid-masonry-container-style-2").fadeIn("slow");
  $(".grid-masonry-container-style-2").imagesLoaded(function() {
    $(".grid-masonry-container-style-2").isotope({
      itemSelector: ".masonry-grid-item",
      layoutMode: "fitRows"
    });
    $(".grid-masonry-container-style-2").isotope("layout");
  });

});
