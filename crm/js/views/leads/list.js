define([
  'App',
  'views/view',
  'text!templates/lead/list.html'
], 
function(App, BaseView, tmpl) {

  var View = {
    tagName   : 'div',
    className : 'eight columns',
    id        : 'leads',

    tmpl      : tmpl,

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

    initialize: function(options) {
      console.log('\r\LeadListView::initialize');

      this.collection = options.collection;
    }
  };

  var LeadListView = Backbone.View.extend(_.extend(View, new BaseView()));

  return LeadListView;
});