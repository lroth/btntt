define([], function() {
	var LeadModel = Backbone.Model.extend({
		defaults: {
			alert		: '',
			description	: "",
			email		: "",
			name		: ""
		},
	});

	return LeadModel;
});