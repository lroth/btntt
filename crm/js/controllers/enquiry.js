// Generated by CoffeeScript 1.6.2
/* global define, Marionette, _
*/


(function() {
  define(["app", "core/controller"], function(App, BaseController) {
    "use strict";
    var Controller, EnquiryController;

    Controller = {
      modelName: "enquiry"
    };
    EnquiryController = Marionette.Controller.extend(Controller);
    _.extend(EnquiryController.prototype, new BaseController());
    return EnquiryController;
  });

}).call(this);
