(function(jQuery){
	var initLayout = function() {
		var hash = window.location.hash.replace('#', '');
        
		var currentTab = jQuery('ul.navigationTabs a')
							.bind('click', showTab)
							.filter('a[rel=' + hash + ']');
        //vst                   
		if (currentTab.size() == 0) {
			currentTab = jQuery('ul.navigationTabs a');
		}
         var initcolor=jQuery('#color').val();
         var indexposition=0;
         if(initcolor=='Orange') indexposition=1;
         if(initcolor=='Yellow')indexposition=2;
         if(initcolor=='Green') indexposition=3;
         if(initcolor=='Blue') indexposition=4;
         if(initcolor=='Dark Blue') indexposition=5;
         if(initcolor=='Purple') indexposition=6;
        //endvst
       
		showTab.apply(currentTab.get(indexposition));
        
	};
	
	var showTab = function(e) {
	   
		var tabIndex = jQuery('ul.navigationTabs a')
							.index(this);
         
		jQuery(this)
			.blur();
		jQuery('div.tab')
			.hide()
				.eq(tabIndex)
				.show();
	};
	
	EYE.register(initLayout, 'init');
})(jQuery)
 jQuery.noConflict();