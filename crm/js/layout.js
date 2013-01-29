define([], function(){
  Layout = Backbone.Marionette.Layout.extend({
    template: "#main",
 
    regions: {
      left      : "#content-left",
      right     : "#content-right",
    }
  });

  return Layout;
});
