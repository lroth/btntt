define([
  'models/lead', 
  'collections/lead',

  'views/leads/add',
  'views/leads/list',
  'views/leads/search'
], 
function(LeadModel, LeadCollection, ViewAdd, ViewList, ViewSearch) {

  var LeadsMainView = Backbone.View.extend({
    tagName : 'div',
    id      : 'leads',

    render: function() {
      this.$el.empty();
      this.renderSubViews();
    },

    renderSubViews : function() {
      _.each(this.subViews, this.renderSubView, this);
    },

    renderSubView : function(view) {
      this.$el.append(view.$el);
      view.render();
    },

    initialize: function(options) {
      console.log('LeadsMainView::initialize');
      
      this.collection = new LeadCollection(options);
      this.model      = new LeadModel(options);

      this.subViews = {
        add   : new ViewAdd(),
        search: new ViewSearch(),
        list  : new ViewList()
      };
    }
  });

  return LeadsMainView;
});