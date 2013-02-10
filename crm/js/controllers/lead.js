define(['App'], function(App) {
	var LeadController = Marionette.Controller.extend({

		initialize: function(options) {
			console.log('LeadController::initialize');
		},

		leadsList: function() {
			console.log('LeadController::leadsList\r\n');

			var options = {
				url 		: {
					api  : App.getUrl('api', ''),
					rest : App.getUrl('rest', 'lead')
				},
				modelName : 'lead' 
			};

			require(['views/leads/main'], function(LeadsMainView) {
				App.content.show(new LeadsMainView(options));
			});
		}

	});

	return LeadController;
});