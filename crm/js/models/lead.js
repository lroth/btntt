define(['moment'], function() {
	var LeadModel = Backbone.Model.extend({
		defaults: {
			alert		: '',
			description	: "",
			email		: "cypherq@gmail.com",
			name		: ""
		},
	});

	return LeadModel;
});