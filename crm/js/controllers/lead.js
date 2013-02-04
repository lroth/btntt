define(['App'], function(App) {
	var LeadController = Marionette.Controller.extend({

		initialize: function(options) {
			console.log('LeadController::initialize');

			this.restUrl = App.getRestUrl('lead');
		},

		leadsList: function() {
			console.log('LeadController::leadsList\r\n');

			var options = {
				url 		: this.restUrl
			};

			require(['views/leads/main'], function(LeadsMainView) {
				App.content.show(new LeadsMainView(options));
			});
		}

	});

	return LeadController;
});