/* =================================================/
/============  Mega Menu JavaScript ================/
/=========  This javascript is used to assist ======/
/========  the mega menu css to control this  ======/
/==============   menus' visibility   ==============/
/==================================================*/
+ function($) {
    'use strict';
    // Selecting menu items by index 
    var mMenuNav = $('.header-nav nav'),
        mMenuNavH2 = $('.header-nav nav h2'),
        menuItemOne = $('.header-nav .navbar-nav > li').eq(0),
        menuItemTwo = $('.header-nav .navbar-nav > li').eq(1),
        menuItemThree = $('.header-nav .navbar-nav > li').eq(2),
        menuItemFour = $('.header-nav .navbar-nav > li').eq(3),
        menuItemFive = $('.header-nav .navbar-nav > li').eq(4);

    $(mMenuNav).addClass('megamenu'); // adding mega menu class to drupal mega menu
    $(mMenuNavH2).remove(); // remove hidden h2 for screen reader

    // Appending mega menu items
    $('.filing').appendTo(menuItemOne);
    $('.payments').appendTo(menuItemTwo);
    $('.refunds').appendTo(menuItemThree);
    $('.cnd').appendTo(menuItemFour);
    $('.fni').appendTo(menuItemFive);

    // Adding hover style on hover of sub navigation
    $('.filing').hover(function() {
        $((menuItemOne)[0].firstElementChild).toggleClass('mm-is-active');
    });
    $('.payments').hover(function() {
        $((menuItemTwo)[0].firstElementChild).toggleClass('mm-is-active');
    });
    $('.refunds').hover(function() {
        $((menuItemThree)[0].firstElementChild).toggleClass('mm-is-active');
    });
    $('.cnd').hover(function() {
        $((menuItemFour)[0].firstElementChild).toggleClass('mm-is-active');
    });
    $('.fni').hover(function() {
        $((menuItemFive)[0].firstElementChild).toggleClass('mm-is-active');
    });

// Adding bootstrap classes to sections
    // file link
    if ($('.filing section').length == 1) {
        $('.filing section').addClass('col-lg-12');
    } else if ($('.filing section').length == 2) {
        $('.filing section').addClass('col-lg-6');
    } else if ($('.filing section').length == 3) {
        $('.filing section').addClass('col-lg-4');
    } else if ($('.filing section').length == 4) {
        $('.filing section').addClass('col-lg-3');
    }
    // payments link
    if ($('.payments section').length == 1) {
        $('.payments section').addClass('col-lg-12');
    } else if ($('.payments section').length == 2) {
        $('.payments section').addClass('col-lg-6');
    } else if ($('.payments section').length == 3) {
        $('.payments section').addClass('col-lg-4');
    } else if ($('.payments section').length == 4) {
        $('.payments section').addClass('col-lg-3');
    }
    // refunds link
    if ($('.refunds section').length == 1) {
        $('.refunds section').addClass('col-lg-12');
    } else if ($('.refunds section').length == 2) {
        $('.refunds section').addClass('col-lg-6');
    } else if ($('.refunds section').length == 3) {
        $('.refunds section').addClass('col-lg-4');
    } else if ($('.refunds section').length == 4) {
        $('.refunds section').addClass('col-lg-3');
    }
    // credits and deduction link
    if ($('.cnd section').length == 1) {
        $('.cnd section').addClass('col-lg-12');
    } else if ($('.cnd section').length == 2) {
        $('.cnd section').addClass('col-lg-6');
    } else if ($('.cnd section').length == 3) {
        $('.cnd section').addClass('col-lg-4');
    } else if ($('.cnd section').length == 4) {
        $('.cnd section').addClass('col-lg-3');
    }
    // forms and publications link
    if ($('.fni section').length == 1) {
        $('.fni section').addClass('col-lg-12');
    } else if ($('.fni section').length == 2) {
        $('.fni section').addClass('col-lg-6');
    } else if ($('.fni section').length == 3) {
        $('.fni section').addClass('col-lg-4');
    } else if ($('.fni section').length == 4) {
        $('.fni section').addClass('col-lg-3');
    }

   // remove anchor bottom border from block with descriptions
   $('.paragraph--type--ocms-mega-menu-item').each(function() {
        if ($(this).children('div').hasClass('field--name-field-ocms-text-plain-long')) {
            $(this).find('.field--name-field-ocms-callout-link a').css('border-bottom','none');
        }
   });

    // initialize the megamenu
    $(document).ready(function () {
        // initialize the megamenu
         $(".megamenu").accessibleMegaMenu();
       
        // hack so that the megamenu doesn't show flash of css animation after the page loads.
        setTimeout(function () {
            $('body').removeClass('init');
        }, 500);
    });
}(jQuery);