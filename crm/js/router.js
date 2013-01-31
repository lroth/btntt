/* 
 * There we will specify other controllers to store routes in one place
 * while keeping decomposited structure of controllers
*/

define(['controllers/lead'], function(LeadController) {
  "use strict";

  /* Define routes for leads/ */
  var LeadRouter = Marionette.AppRouter.extend({
    appRoutes: {

      /* 'route' : controllerMethodName */
      ""          : "leadsList",
      "lead/"     : "leadsList",
      "*actions"  : "leadsList"
    }
  });

  /* 
   * Initialize all routes.
   * We will add some magic here when project will grow up 
   */
  var initialize = function() {
      console.log('INITIALIZE: Router\r\n');

      /* Marionette router will use method from passed controller */
      var leadRouter = new LeadRouter({
        controller: new LeadController()
      });

      /* tell to backbone where is root of application */
      Backbone.history.start({pushState: true, root: "/crm/#"});
    };

  return {
    initialize: initialize
  };
});