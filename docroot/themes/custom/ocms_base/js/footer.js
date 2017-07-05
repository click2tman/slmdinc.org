/* ================================================/
/===========  AccordionJavascript ====================/
/========  This is used to apply dynamically =======/
/==========  Accordion classes and ids   ===========/
/=============================================== */
;(function($){
    'use strict';
    // Setting the ocms-footer Accordion object
    var Accordion = Accordion || {},
        foot = $('.ocms-footer').find(':header'), // finding all ocms-footer headers
        fnav = $('.ocms-footer').find('ul'); // finding all ocms-footer uls

    Accordion = {
        // Setup mobile view
        addingClsNids: function(elem) {            
                $(fnav).addClass('collapse');
                $(foot).addClass("collapsed");  
                $(fnav).each(function(item, ele) { // for ul interactions
                    elem = $(ele).attr('id', 'ocms-foot-collapse-' + (item + 1)); 
                });  
                $(foot).each(function(item, ele) { // for h2 interactions
                    elem = $(ele).attr({
                        "data-toggle" : "collapse",
                        "data-parent" : "#ocms-footer-accordion",
                        "href"        : "#ocms-foot-collapse-"  + (item + 1)
                    });
                });                      
                $(foot).click(function(event) {
                    if($(event.currentTarget).siblings('ul').hasClass('collapse')) {
                        $(this).addClass("up").toggleClass("collapsed");
                        $(this).find('ul').toggleClass('collapse');
                    } else {
                        $(this).addClass("collapsed").toggleClass("up");
                    }
                });
            return false;
        },
        // Setup desktop view        
        removingClsNids: function() { 
            $(fnav).attr({'id':'', 'ocms-foot-collapse-': ''});                        
            $(fnav).removeClass('collapse');
            $(foot).attr({"data-toggle" : "","data-parent" : "","href" : ""}).removeClass('collapsed');
            return false;
        }
    }
    $(window).resize( function() {
        if (window.innerWidth <= 768) { // mobile views
            Accordion.addingClsNids();
        } else {
            Accordion.removingClsNids();
        }
    });
})(jQuery);