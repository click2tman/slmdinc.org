jQuery(document).ready(function($) {
  if (jQuery("#affix").length>0) {
    $(window).load(function() {

      var affixBottom = $("#footer").outerHeight(true)
      + $("#subfooter").outerHeight(true)
      + $("#footer-top").outerHeight(true)
      + $("#page").outerHeight(true)
      - $("#block-ocms_corporate-content").outerHeight(true),
      affixTop = $("#affix").offset().top;

      //The #header height
      var staticHeaderHeight = $("#header").outerHeight(true);

      //The #header height onscroll while fixed (it is usually smaller than staticHeaderHeight)
      //We can not calculate it because we need to scroll first
      var fixedheaderHeight = drupalSettings.ocms_corporate.nodeSideAffix.affixFixedHeaderHeigth;

      //The admin overlay menu height
      var adminHeight = drupalSettings.ocms_corporate.nodeSideAffix.affixAdminHeigth;

      function initializeAffix(topAffix) {
        if ($(".fixed-header-enabled").length>0) {
          if ($(".user-logged-in").length>0) {
            affixBottom = affixBottom-staticHeaderHeight-fixedheaderHeight-adminHeight-15;
            initAffixTop = topAffix-fixedheaderHeight-adminHeight-15; //The fixedheaderHeight and adminHeight are added as padding on the page so we need to remove it from affixTop
          } else {
            affixBottom = affixBottom-staticHeaderHeight-fixedheaderHeight-15;
            initAffixTop = topAffix-fixedheaderHeight-15;  //The fixedheaderHeight is added as padding on the page so we need to remove it from affixTop
          }
        } else {
          if ($(".user-logged-in").length>0) {
            affixBottom = affixBottom;
            initAffixTop = topAffix-adminHeight-15; // The adminHeight is added as padding on the page so we need to remove it from affixTop
          } else {
            affixBottom = affixBottom+adminHeight;
            initAffixTop = topAffix-15; //We reduce by 15 to make a little space between the window top and the #affix element
          }
        }
        $("#affix").affix({
          offset: {
            top: initAffixTop,
            bottom: affixBottom
          }
        });
      }

      initializeAffix(affixTop);

      $("#affix").on("affixed.bs.affix", function () {
        //We set through JS the inline style top position
        if ($(".fixed-header-enabled").length>0) {
          if ($(".user-logged-in").length>0) {
            $("#affix").css("top", (fixedheaderHeight + adminHeight + 15)+"px");
          } else {
            $("#affix").css("top", (fixedheaderHeight + 15)+"px");
          }
        } else {
          if ($(".user-logged-in").length>0) {
            $("#affix").css("top", (adminHeight + 15)+"px");
          } else {
            $("#affix").css("top", (15)+"px");
          }
        }
      });

    });
  }
});
