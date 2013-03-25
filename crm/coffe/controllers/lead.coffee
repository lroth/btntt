#global define
#global Marionette
#global _

define ["core/controller"], (BaseController) ->
  "use strict"
  Controller = modelName: "lead"
  LeadController = Marionette.Controller.extend(Controller)
  _.extend LeadController::, new BaseController()
  LeadController
