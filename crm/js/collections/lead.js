define(['models/lead'], function(LeadModel) {
	var LeadCollection = Backbone.Collection.extend({
		model 	: LeadModel,

		initialize : function(options) {
			this.url = options.url.rest;
		}
	});

	return LeadCollection;
});