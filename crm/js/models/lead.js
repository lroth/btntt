define([], function() {
	var LeadModel = Backbone.Model.extend({
		defaults: {
			alert		: '',
			created_at	: new Date().toISOString(),
			updated_at	: new Date().toISOString(),
			description	: "",
			email		: "cypherq@gmail.com",
			name		: ""
		},
	});

	return LeadModel;
});