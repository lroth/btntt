#global define
#global Marionette
#global Backbone

# There we will specify other controllers to store routes in one place
# while keeping decomposited structure of controllers
define ["../controllers/lead", "controllers/enquiry"], (LeadController, EnquiryController) ->
  "use strict"
  
  # Define routes for leads/
  LeadRouter = Marionette.AppRouter.extend(appRoutes:
    
    # 'route' : controllerMethodName
    leads: "show"
  )
  EnquiryRouter = Marionette.AppRouter.extend(appRoutes:
    enquiries: "show"
  )
  
  # Initialize all routes.
  # We will add some magic here when project will grow up
  initialize = ->
    
    # `Marionette` router will use method from passed controller
    leadRouter = new LeadRouter(controller: new LeadController())
    enquiryRouter = new EnquiryRouter(controller: new EnquiryController())
    Backbone.history.start()

  initialize: initialize
