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
      'click .remove' : 'removeLead',
      'click .edit'   : 'editLead',
    },

    onLeadRemoved : function(leadView, collection, response) {
      this.removeLeadView(leadView);
      App.vent.trigger('layout:message', { type: "success", message : response.message});
    },

    removeLead : function(e) {
      var leadView = $(e.target).closest('tr')
          , leadId = $(leadView).attr('data-model-id')
          , model  = this.collection.get(leadId)
          ;

      if(!_.isUndefined(model)) {
        model.destroy({
          success : _.bind(this.onLeadRemoved, this, leadView)
        });
      }
    },

    editLead : function(e) {
      var leadView = $(e.target).closest('tr')
          , leadId = $(leadView).attr('data-model-id')
          , model  = this.collection.get(leadId)
          ;

        App.vent.trigger('lead:edit', model);
    },

    removeLeadView : function(leadView) {
      $(leadView).hide('slow', function(){ $(leadView).remove(); });
    },

    initialize: function(options) {
      console.log('\r\LeadListView::initialize');

      this.collection = options.collection;
      App.vent.on('lead:add', _.bind(this.render, this));
    }
  };

  var LeadListView = Backbone.View.extend(_.extend(View, new BaseView()));

  return LeadListView;
});