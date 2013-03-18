//global define
//global Marionette

define(['App'], function (App) {
    "use strict";

    var LeadController = Marionette.Controller.extend({

        initialize: function (options) {
        },

        leadsList: function () {

            var options = {
                url      : {
                    api : App.getUrl('api', ''),
                    rest: App.getUrl('rest', 'lead')
                },
                modelName: 'lead'
            };

            require(['views/leads/main'], function (LeadsMainView) {
                App.content.show(new LeadsMainView(options));
            });
        }

    });

    return LeadController;
});