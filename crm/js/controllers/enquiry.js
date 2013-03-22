//global define
//global Marionette
//global _

define(['App', 'core/controller'], function (App, BaseController) {
    "use strict";

    var Controller = {
        modelName: 'enquiry'
    };

    var EnquiryController = Marionette.Controller.extend(Controller);
    _.extend(EnquiryController.prototype, new BaseController());

    return EnquiryController;
});