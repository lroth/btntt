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
        formData[$(input).attr('placeholder')] = $(input).val();
      });

      return formData;
    },

    addLead : function(e) {
      var formData = this.getFormData();

      this.collection.create(
        formData, 
        { 
          silent: true, 
          wait  : true, 
          success : _.bind(this.addSuccessCallback, this),
          error   : _.bind(this.addErrorCallback, this) 
        });
      
      return false;
    },

    addErrorCallback : function(collection, response) {
      var objResponse = JSON.parse(response.responseText);

      if(!_.isUndefined(objResponse.errors)) { 
        this.cleanErrors();
        this.showErrors(objResponse.errors);
      }
    },

    showErrors : function(errors) {      
      for(var i in errors) {
        var small = '<small style="display:none;" class="error">' + errors[i] + '</small>';
        $('input[name=' + i + ']').addClass('error').after(small);
        $('small.error').fadeIn('normal');
      }
    },

    addSuccessCallback : function(collection, response) {
      this.cleanErrors();

      App.vent.trigger('layout:message', { type: "success", message : response.message});
      App.vent.trigger('lead:add');
    },

    cleanErrors : function() {
      $('small.error').remove();
      $('input.error').removeClass('error');
    },

    customRender: function() {
      $.get(this.options.url.api + 'get/form/lead', _.bind(function(response) {
        response = JSON.parse(response).form;
        this.$el.append(this.getHtml(response));
      }, this));
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