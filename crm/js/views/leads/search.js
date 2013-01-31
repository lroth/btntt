define([
  'text!templates/lead/search.html'
], 
function(tmpl) {

  var LeadSearchView = Backbone.View.extend({
    tagName   : 'div',
    id        : 'search',
    className : 'eight columns',

    render: function() {
      this.$el.html(tmpl);
    },

    initialize: function(options) {
      console.log('LeadSearchView::initialize()');
    }
  });

  return LeadSearchView;
});