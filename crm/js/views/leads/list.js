define([
  'App',
  'text!templates/lead/list.html'
], 
function(App, tmpl) {

  var LeadListView = Backbone.View.extend({
    tagName   : 'div',
    className : 'eight columns',
    id        : 'leads',

    events: {
      'click .remove': 'removeLead',
    },

    onLeadRemoved : function(leadView, response) {
      this.removeLeadView(leadView);
      App.vent.trigger('layout:message', response.message);
    },

    removeLead : function(e) {
      var leadView = $(e.target).closest('tr')
          , leadId = $(leadView).attr('data-model-id')
          ;

      this.leadsCollection.get(leadId).destroy({
        success : _.bind(this.onLeadRemoved, this, leadView)
      });
    },

    removeLeadView : function(leadView) {
      $(leadView).hide('slow', function(){ $(leadView).remove(); });
    },

    getHtml: function(response) {
      var tmplCompiled = Handlebars.compile(tmpl);

      return tmplCompiled({
        leads: response
      });
    },

    render: function() {
      this.collection.fetch({
        success: _.bind(function(collection, response) {
          this.$el.append(this.getHtml(response));
        }, this)
      })
    },

    initialize: function(options) {
      console.log('\r\LeadListView::initialize');

      this.leadsCollection = options.collection;
    }
  });

  return LeadListView;
});