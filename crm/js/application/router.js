//global define
//global Marionette
//global Backbone

// There we will specify other controllers to store routes in one place
// while keeping decomposited structure of controllers

define([
    '../controllers/lead', 'controllers/enquiry'
],
    function (LeadController, EnquiryController) {
        "use strict";

        // Define routes for leads/
        var LeadRouter = Marionette.AppRouter.extend({
            appRoutes: {
                // 'route' : controllerMethodName
                'leads': 'show'
            }
        });

        var EnquiryRouter = Marionette.AppRouter.extend({
            appRoutes: {
                'enquiries': 'show'
            }
        });

        // Initialize all routes.
        // We will add some magic here when project will grow up
        var initialize = function () {
            // `Marionette` router will use method from passed controller
            var leadRouter = new LeadRouter({
                controller: new LeadController()
            });

            var enquiryRouter = new EnquiryRouter({
                controller: new EnquiryController()
            });

            Backbone.history.start();
        };

        return {
            initialize: initialize
        };
    });