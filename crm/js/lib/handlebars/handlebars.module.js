define(['handlebars'], function(){
	Handlebars.registerHelper('leadDateFormat', function(date) {
	    var date  = new Date(Date.parse(date))
	        , sep = '/';

		return date.getDate() + sep + (date.getMonth() + 1) + sep + date.getFullYear()
	});
});