define([
  'collections/lead',

  'views/leads/add',
  'views/leads/list',
  'views/leads/search'
], 
function(LeadCollection, ViewAdd, ViewList, ViewSearch) {

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
      console.log('LeadsMainView::initialize() \r\n');
      
      this.collection     = new LeadCollection(options);
      options.collection  = this.collection;

      this.subViews = {
        add   : new ViewAdd(options),
        search: new ViewSearch(options),
        list  : new ViewList(options)
      };
    }
  });

  return LeadsMainView;
});