define(['models/lead'], function(LeadModel) {
	var LeadCollection = Backbone.Collection.extend({
		model: LeadModel
	});

	return LeadCollection;
});