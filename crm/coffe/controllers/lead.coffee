#global define
#global Marionette
#global _

define ["app", "core/controller"], (App, BaseController) ->
  "use strict"
  Controller = modelName: "lead"
  LeadController = Marionette.Controller.extend(Controller)
  _.extend LeadController::, new BaseController()
  LeadController
