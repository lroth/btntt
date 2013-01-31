define([
  'text!templates/lead/search.html'
], 
function(tmpl) {

  var LeadSearchView = Backbone.View.extend({
    tagName   : 'div',
    id        : 'search',

    render: function() {
      this.$el.html(tmpl);
    },

    initialize: function(options) {
      console.log('eadSearchView::initialize');
    }
  });

  return LeadSearchView;
});