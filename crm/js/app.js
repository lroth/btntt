define(['router'], function(router) {
	console.log('app require');
	"use strict";

	var App 	= new Marionette.Application();

	App.url = {
		api 	: '/web/api/',
		rest 	: '/web/rest/'
	};

	App.initializeLayout = function() {
		// Initialize layout view
		require(['views/layout/main'], function(LayoutView) {
			this.layoutView = new LayoutView();
		});
	};

	// prepend host to every url from `App.url`
	App.setUrls = function() {
		var origin = Backbone.history.location.origin; 
		for(var i in this.url) { this.url[i] = origin + this.url[i]; }
	};

	// helper for accessing url's
	App.getUrl = function(type, route) {
		return this.url[type] + route;
	};	

	App.initApp = function() {
		// non sense method a little bit but temporary I can laeve it here
		$.get(this.url.api + 'get/init-data/', _.bind(this.onInitData, this));
	};


	App.onInitData = function(response){
		var initData = JSON.parse(response);
		
		if(!initData.user.auth) { 
			// redirect to login page if not logged
			window.location = initData.baseUrl + '/login'; 
		}
		else { 
			// set user global data
			this.userData = initData.user; 

			// update defined urls with root 
			this.setUrls();
			
			// Initialize whole routing here 
			router.initialize();
		}
	};

	// Add layout regions here to controll views
	App.addRegions({
		content : "#content"
	});

	App.addInitializer(function() {
		console.log('INITIALIZE: Application');

		this.initApp();
	});

	return App;
});