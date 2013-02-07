define(['handlebars'], function(){
	Handlebars.registerHelper('leadDateFormat', function(date) {
		return moment(date).calendar();
	});

	Handlebars.registerHelper('formInputWidget', function() {
		var inputStr = '';

		if(this.type !== 'textarea') {

	    	inputStr = 
	    		'<input data-format="' 	+ this.format + 
	    		'" type="' 				+ this.type + 
	    		'" placeholder="' 		+ this.name +
	    		'" name="' 				+ this.name + '"/>'
    		;	
		}
		else {
			inputStr = 
				'<textarea name="' 		+ this.name +
				'">Add your ' 			+ this.name + '...</textarea>'
		}

		return new Handlebars.SafeString(inputStr);
	});
});