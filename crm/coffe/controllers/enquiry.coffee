### global define, Marionette, _ ###;

define ["core/controller"], (BaseController) ->
  "use strict"
  Controller = modelName: "enquiry"
  EnquiryController = Marionette.Controller.extend(Controller)
  _.extend EnquiryController::, new BaseController()
  EnquiryController
