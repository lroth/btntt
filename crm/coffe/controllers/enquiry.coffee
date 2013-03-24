### global define, Marionette, _ ###;

define ["app", "core/controller"], (App, BaseController) ->
  "use strict"
  Controller = modelName: "enquiry"
  EnquiryController = Marionette.Controller.extend(Controller)
  _.extend EnquiryController::, new BaseController()
  EnquiryController
