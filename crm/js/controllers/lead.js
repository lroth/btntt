define(['collections/lead', 'models/lead'], function(LeadController) {
	var LeadController = Marionette.Controller.extend({

		initialize: function(options) {
			console.log('LeadController initialize...');
		},

		leadsList : function() {
			console.log('LeadsListing...');

			require(['views/leads/list'], function(List){
				console.log(new List);	
			});
		}

	});

	return LeadController;
});