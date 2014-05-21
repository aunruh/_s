var spa;

spa = (function(){
	var configMap = {
		example : 15,
		ajaxContentData : {
			action: 'get_content',
			id: null
		}
	},
	stateMap = {
		history : null,
	},
	// jquery containers
	jqueryMap = {
		$primary : null,
		$menulinks : null,
		$body : null
	},
	// module scope function names
	setjqueryMap, initModule, initHistory, swapContent, onMenuClick;

	onMenuClick = _.throttle(function(e){
		
		var href = $(this).attr('href');
		var rawTitle = $(this).text();
		var title = rawTitle+" — "+passedData.title;
		var id = parseInt($(this.parentNode).attr('id'), 10);
		rawTitle = rawTitle.toLowerCase();
		rawTitle = rawTitle.replace(" ", "-");

		if( id != stateMap.history.data.pageId ){
			configMap.ajaxContentData.id = id;
			
			History.pushState({pageId: id, rawTitle: rawTitle}, title, href);
			stateMap.history = History.getState();
		}

		return false;

	}, 1000);

	swapContent = function(){
		var newContent;
		jQuery.when(
			
			jQuery.ajax({
				type: 'POST',
				url: passedData.ajaxUrl,
				data: configMap.ajaxContentData,
				success: function(result) {
					newContent = result;
				}
			})

			//add more ajax calls here

			).then(function() {
				jqueryMap.$primary.html(newContent);
				jqueryMap.$body.removeClass().addClass('id-'+stateMap.history.data.pageId).addClass('title-'+stateMap.history.data.rawTitle);
			}
		);
	};

	initHistory = function(){
		//save initial state to browser history
		var origTitle = document.title;
		History.pushState({ pageId: jqueryMap.$primary.attr('data-id'), rawTitle: origTitle }, origTitle, document.URL);
		stateMap.history = History.getState();

		// Bind to StateChange Event
		History.Adapter.bind(window,'statechange',_.throttle(function(){ // Note: We are using statechange instead of popstate
			stateMap.history = History.getState(); // Note: We are using History.getState() instead of event.state
			swapContent();
		},300));
	};

	setjqueryMap = function(){
		jqueryMap.$primary = jQuery('#primary');
		jqueryMap.$menulinks = jQuery('.main-navigation a');
		jqueryMap.$body = jQuery('body');
	};

	initModule = function(){
		setjqueryMap();
		initHistory();
		jqueryMap.$menulinks.click(onMenuClick);
		console.log('init');
	};

	return { initModule : initModule };

}());

jQuery(document).ready(function(){
	spa.initModule();
});