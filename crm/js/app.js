define(['router'], function(router) {
	console.log('app require');
	"use strict";

	var App 	= new Marionette.Application();

	App.url = {
		api 	: '/web/api/',
		rest 	: '/web/rest/'
	};

	App.initializeLayout = function() {
		/* Initialize layout view */
		require(['views/layout/main'], function(LayoutView) {
			this.layoutView = new LayoutView();
		});
	};

	App.setUrls = function() {
		var origin = Backbone.history.location.origin; 
		for(var i in this.url) { this.url[i] = origin + this.url[i]; }
	};

	App.getUrl = function(type, route) {
		return this.url[type] + route;
	};	

	/* Add layout regions here to controll views */
	App.addRegions({
		content : "#content"
	});

	App.addInitializer(function() {
		console.log('INITIALIZE: Application');

		this.setUrls();
		// Initialize whole routing here 
		router.initialize();
	});

	return App;
});