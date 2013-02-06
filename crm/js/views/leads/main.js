define([
  'collections/lead',

  'views/leads/form',
  'views/leads/list',
  'views/leads/search'
], 
function(LeadCollection, ViewForm, ViewList, ViewSearch) {

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
      console.log('LeadsMainView::initialize \r\n');
      
      this.collection     = new LeadCollection(options);
      options.collection  = this.collection;

      this.subViews = {
        form   : new ViewForm(options),
        search : new ViewSearch(options),
        list   : new ViewList(options)
      };
    }
  });

  return LeadsMainView;
});