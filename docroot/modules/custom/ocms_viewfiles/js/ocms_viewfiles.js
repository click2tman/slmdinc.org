(function($) {
	Drupal.behaviors.myBehavior = {
  attach: function (context, settings) {
	$("#myTable").tablesorter(); 
	if($('#pager').length >0 ){
  
      $("#myTable").tablesorterPager({container: $("#pager")}); 
    }
	
	
	  }
};
  
})(jQuery);		
