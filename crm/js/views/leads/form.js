define([
    'App', 
    'core/form', 
    'text!templates/lead/form.html'
  ], function(App, BaseForm, tmpl) {

  var LeadFormView = Backbone.View.extend({
    tmpl          : tmpl,
    
    initialize : function(options) {
      console.log('LeadFormView::initialize');
      
      App.vent.on('lead:edit', _.bind(this.setEditMode, this) );   

      this.options = options;
      this.getCsfrToken();
    }
  });
  
  _.extend(LeadFormView.prototype, new BaseForm());
  
  return LeadFormView;
}); 