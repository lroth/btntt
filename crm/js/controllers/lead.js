//global define
//global Marionette
//global _

define(['App', 'core/controller'], function (App, BaseController) {
    "use strict";

    var Controller = {
        modelName: 'lead'
    };

    var LeadController = Marionette.Controller.extend(Controller);
    _.extend(LeadController.prototype, new BaseController());

    return LeadController;
});