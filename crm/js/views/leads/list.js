define([
  'text!templates/lead/list.html', 
  'models/lead', 
  'collections/lead'
], 
function(tmpl, LeadModel, LeadCollection) {

  var LeadListView = Backbone.View.extend({
    tagName   : 'div',
    className : 'list',
    id        : 'leads',

    getHtml: function(response) {
      var tmplCompiled = Handlebars.compile(tmpl);

      return tmplCompiled({
        leads: response
      });
    },

    render: function() {
      // this.collection.fetch({
      //   success: _.bind(function(collection, response) {
      //     this.$el.append(this.getHtml(response));
      //   }, this)
      // })
    },

    initialize: function(options) {
      // console.log('\r\nInitalize list view');

      // this.collection = new LeadCollection(options);
      // this.model = new LeadModel();
    }
  });

  return LeadListView;
});