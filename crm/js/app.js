define(['router'], function(router) {
	console.log('app require');
	"use strict";

	var App 	= new Marionette.Application();
	App.restUrl = '/web/api/';

	App.getRestUrl = function(route) {
		return Backbone.history.location.origin + this.restUrl + route;
	};	

	/* Add layout regions here to controll views */
	App.addRegions({
		content : "#content"
	});

	App.addInitializer(function() {
		console.log('INITIALIZE: Application');

		// Initialize whole routing here 
		router.initialize();
	});

	App.initializeLayout = function() {
		/* Initialize layout view */
		require(['views/layout/main'], function(LayoutView) {
			this.layoutView = new LayoutView();
		});
	};

	return App;
});