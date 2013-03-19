//global define
//global Marionette

define(['App'], function (App) {
    "use strict";

    var EnquiryController = Marionette.Controller.extend({

        initialize: function (options) {
        },

        enquiryList: function () {

            var options = {
                url      : {
                    api : App.getUrl('api', ''),
                    rest: App.getUrl('rest', 'enquiry')
                },
                modelName: 'enquiry'
            };

            require(['views/enquiries/main'], function (EnquiryMainView) {
                App.content.show(new EnquiryMainView(options));
            });
        }

    });

    return EnquiryController;
});