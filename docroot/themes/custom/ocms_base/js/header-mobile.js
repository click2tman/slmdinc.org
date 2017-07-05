+function ($) {
  'use strict';

	//  Set variables 
	var dropdownBody = $('#block-infomenumobile').find('.dropdown-menu'),
      dropdownBtn = $('#block-infomenumobile').find('.dropdown-toggle'),
      mobileMenuBtns = $('.ocms-header-mobile-menu').children(),
      on = 'none',
      clicked,
      btnThatWasOn,
      faCaretIcon = $('<i class="fa fa-angle-down pull-right" aria-hidden="true"></i>');
  
  $(dropdownBtn).click(function(e){
    e.preventDefault();
  });

  // Change behavior of dropdown in info sub-menu to collapse
  $(dropdownBtn).removeAttr('href data-target data-toggle');
  $('.caret').replaceWith(faCaretIcon);
  $(dropdownBody).attr('class', 'collapse');
  $(dropdownBtn).click(function(){
    $(dropdownBody).collapse('toggle');
  });

  $('#ocms-mobile-navbar-infoBtn').mouseup(function(){
      $(this).removeClass('mobile-navbar-close').addClass('mobile-info-icon');
  });
  $('#ocms-mobile-navbar-infoBtn').mousedown(function(){
      $(this).removeClass('mobile-info-icon').addClass('mobile-navbar-close');
  });

  // Click behavior for mobile menu buttons
  $(mobileMenuBtns).click(function(){
    // var target = $(this).data("target");
    var btnThatWasClicked = $(this);
    var wasClickedTarget = $(btnThatWasClicked).data('target');


    // Version #3: Non-Exclusive Button Toggling

    // Turn off the button that was on, if any, including the clicked button
    if ($(btnThatWasOn) != undefined) {
      $(btnThatWasOn).removeClass('ocms-toggle-onBtn-color');
      switch ($(btnThatWasOn).attr('id')) { 
        case 'ocms-mobile-navbar-searchBtn': 
          $(btnThatWasOn).css({"background-color": "#EAF2FA", "color": "black", "border": "none"});
          break;
        case 'ocms-mobile-navbar-menuBtn': 
          $(btnThatWasOn).addClass('mobile-menu-icon').removeClass('mobile-navbar-close');
          break;		
        default:
      }
      $($(btnThatWasOn).data('target')).collapse('hide');
    }
    // Turn on the clicked button unless it was already on
    if (wasClickedTarget != $(btnThatWasOn).data('target')) {
      switch ($(btnThatWasClicked).attr('id')) { 
        case 'ocms-mobile-navbar-searchBtn': 
          $(btnThatWasClicked).css({"background-color": "#2DA5E5", "color": "white", "border": "1px solid #CCCCCC"});
          break;
        case 'ocms-mobile-navbar-menuBtn': 
          $(btnThatWasClicked).removeClass('mobile-menu-icon').addClass('mobile-navbar-close');
          break;		
        default:
      }
      $(wasClickedTarget).collapse('show');
    }
    // Change the value of btnThatWasOn, but only after we use it above
    if (wasClickedTarget == $(btnThatWasOn).data('target')) {
      btnThatWasOn = undefined;
    } else {
      btnThatWasOn = btnThatWasClicked;
    }

  });

}(jQuery);
