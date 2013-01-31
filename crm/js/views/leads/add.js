define([
  'text!templates/lead/add.html'
], 
function(tmpl) {

  var LeadAddView = Backbone.View.extend({
    tagName   : 'div',
    id        : 'add',

    render: function() {
      this.$el.html(tmpl);
    },

    initialize: function(options) {
      console.log('LeadAddView::initialize');
    }
  });

  return LeadAddView;
});