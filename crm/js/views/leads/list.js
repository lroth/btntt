define(['text!templates/lead/list.html'], function(tmpl){
  var LeadListView = Backbone.View.extend({
    el      : $('#content-right'),
    render  : function(){
      
      var data = {};
      var compiledTemplate = _.template( projectListTemplate, data );
      // Append our compiled template to this Views "el"
      this.$el.append( compiledTemplate );
    }
  });
  
  return LeadListView;
});