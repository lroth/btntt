define([
  'text!templates/lead/add.html'
], 
function(tmpl) {

  var LeadAddView = Backbone.View.extend({
    tagName   : 'div',
    className : 'four columns',
    id        : 'lead-form',

    render: function() {
      this.$el.html(tmpl);
    },

    initialize: function(options) {
      console.log('LeadAddView::initialize');
    }
  });

  return LeadAddView;
});