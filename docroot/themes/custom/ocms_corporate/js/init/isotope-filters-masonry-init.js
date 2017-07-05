jQuery(document).ready(function($) {
  var masonryContainer = $(".grid-masonry-isotope-container"),
  filtersMasonry = $(".view-promoted-items-masonry .filters");
  filtersMasonry.prepend( "<li class=\"active\"><a href=\"#\" data-filter=\"*\">" + drupalSettings.ocms_corporate.isotopeFiltersMasonryInit.isotopeFiltersText + "</a></li>" );

  $(".grid-masonry-isotope-container, .view-promoted-items-masonry .filters").fadeIn("slow");

  masonryContainer.imagesLoaded(function() {
    masonryContainer.isotope({
      itemSelector: ".masonry-grid-item",
      layoutMode : "masonry",
      transitionDuration: "0.6s",
      filter: "*"
    });
    filtersMasonry.find("a").click(function(){
      var $this = $(this);
      var selector = $this.attr("data-filter").replace(/\s+/g, "-");
      filtersMasonry.find("li.active").removeClass("active");
      $this.parent().addClass("active");
      masonryContainer.isotope({ filter: selector });
      return false;
    });
    masonryContainer.isotope("layout");
  });
});
