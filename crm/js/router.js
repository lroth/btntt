//global define
//global Marionette
//global Backbone

// There we will specify other controllers to store routes in one place
// while keeping decomposited structure of controllers

define([
    'controllers/lead', 'controllers/enquiry'
],
    function (LeadController, EnquiryController) {
        "use strict";

        // Define routes for leads/
        var LeadRouter = Marionette.AppRouter.extend({
            appRoutes: {
                // 'route' : controllerMethodName
                "leads": "leadsList"
            }
        });

        var EnquiryRouter = Marionette.AppRouter.extend({
            appRoutes: {
                "enquiries": "enquiryList"
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

            // tell to `Backbone` where is `root` of application
            Backbone.history.start({pushState: true, root: "/crm/", silent: false});
            console.log(Backbone.history.fragment);
        };

        return {
            initialize: initialize
        };
    });