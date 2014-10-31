spa.main = (function(){
	var configMap = {
		example : 15
	},
	ajaxContentData = {
		action: 'get_content',
		id: null,
		type: null,
		// can be phone, tablet or desktop
		currentSize : null,
	},
	stateMap = {
		history : null,
		wWidth : verge.viewportW(),
		wHeight : window.innerHeight,
		deviceType : passedData.mobileDetect,
		// can be 'phone', 'desktop' or 'tablet'
		currentSize : 'desktop',
	}
	// jquery containers
	jqueryMap = {
		$primary : null,
		$menulinks : null,
		$body : null
	},
	// module scope function names
	setjqueryMap, initModule, initHistory, swapContent, onMenuClick;

	var onMenuClick = _.throttle(function(e){
		var href = $(this).attr('href');
		var rawTitle = $(this).text();
		var title = passedData.title+" | "+rawTitle;
		var id = parseInt($(this.parentNode).attr('id'), 10);
		rawTitle = rawTitle.toLowerCase();
		rawTitle = rawTitle.replace(" ", "-");

		if( id != stateMap.history.data.pageId ){
			configMap.ajaxContentData.id = id;
			configMap.ajaxContentData.type = 'get_content';

			History.pushState({pageId: id, rawTitle: rawTitle, type: 'get_content'}, title, href);
			stateMap.history = History.getState();
		}

		return false;

	}, 1000);

	var swapContent = function(){
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

	var initHistory = function(){
		//save initial state to browser history
		var origTitle = document.title;
		History.pushState({ pageId: jqueryMap.$primary.attr('data-id'), rawTitle: origTitle }, origTitle, document.URL);
		stateMap.history = History.getState();

		// Bind to StateChange Event
		History.Adapter.bind(window,'statechange',_.throttle(function(){ // Note: We are using statechange instead of popstate
			stateMap.history = History.getState(); // Note: We are using History.getState() instead of event.state
			jQuery('.menu-item').removeClass('current-menu-item current-menu-parent current-post-ancestor current-post-parent');
			swapContent();
		},300));
	};

	var setjqueryMap = function(){
		jqueryMap.$primary = jQuery('#primary');
		jqueryMap.$menulinks = jQuery('.main-navigation a');
		jqueryMap.$body = jQuery('body');
	};

	var initModule = function(){
		setjqueryMap();
		initHistory();
		jqueryMap.$menulinks.click(onMenuClick);
		console.log('init');
	};

	return {
	  initModule : initModule
	}

}());