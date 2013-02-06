define(['App', 'views/view', 'text!templates/lead/form.html'], function(App, BaseView, tmpl) {

  var View = {
    tagName     : 'div',
    className   : 'four columns',
    id          : 'lead-form',

    tmpl        : tmpl,
    editMode    : false,

    events : {
      'click .add': 'addLead'
    },

    getFormData : function() {
      var formData = {};
      
      $.each($('#lead-add input[type=text]'), function(key, input){
        formData[$(input).attr('name')] = $(input).val();
      });

      return formData;
    },

    addLead : function(e) {
      var formData = this.getFormData();
      console.log(formData);
      this.collection.create(formData);
      
      return false;
    },

    customRender: function() {
      $.get(this.options.url.api + 'get/form/lead', _.bind(function(response) {
        response = this.parseForm(JSON.parse(response).form);
        this.$el.append(this.getHtml(response));
      }, this));
    },

    parseForm : function(form) {
      //@cypherq: move this to fields
      var exclude     = ['id', 'createdAt', 'updatedAt'];
      var parsedForm  = [];

      for(var i =0; i < form.length; i++) { 
        if(exclude.indexOf(form[i].name) == -1) { parsedForm.push(form[i]); } 
      }

      return parsedForm;
    },

    getCsfrToken: function() {
      $.get(this.options.url.api + 'get/token', function(response) {
        this.token = response.token;
      });
    },

    initialize: function(options) {
      console.log('LeadFormView::initialize');
      
      this.options = options;
      this.getCsfrToken();
    }
  };
  
  var LeadFormView = Backbone.View.extend(_.extend(View, new BaseView()));
  
  return LeadFormView;
});