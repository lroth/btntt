define(['router'], function(router) {
	"use strict";

	var App = new Marionette.Application();

	App.restUrl = '/web/';

	App.getRestUrl = function(route) {
		return Backbone.history.location.origin + this.restUrl + route;
	}	
	
	App.on("initialize:after", function(){});

	/* Add layout regions here to controll views */
	App.addRegions({
		content : "#content"
	});

	App.addInitializer(function() {
		console.log('INITIALIZE: Application');

		/* Initialize whole routing here */
		router.initialize();
	});

	return App;
});