define(['router'], function(router) {
	"use strict";


	var App = new Marionette.Application();
	
	App.on("initialize:after", function(){
  		
	});



	/* Add layout regions here to controll views */
	App.addRegions({
		left: '#helper',
		main: '#main'
	});

	App.addInitializer(function() {
		console.log('Application initialize...');

		/* Initialize whole routing here */
		router.initialize();
	});

	return App;
});