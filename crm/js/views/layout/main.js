define(['App'], function(App) {
  var LayoutMainView = Backbone.View.extend({
    tagName : 'div',
    id      : 'main',

    events : {

    },

    initialize: function(options) {
      console.log('LayoutMainView::initialize \r\n');
      App.vent.on('layout:message', this.showMessage);
    },

    showMessage: function(message) {
      console.log(message);  
    }
  });

  return LayoutMainView;
});