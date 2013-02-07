define(['App', 'views/view', 'text!templates/lead/form.html'], function(App, BaseView, tmpl) {

  var View = {
    tagName     : 'div',
    className   : 'four columns',
    id          : 'lead-form',

    tmpl        : tmpl,
    editMode    : false,

    events : {
      'click .submit': 'submitLead',
      'click .cancel': 'cancelEdit'
    },

    getFormData : function() {
      var formData = {};
      
      $.each($('#lead-add input[type=text]'), function(key, input){
        formData[$(input).attr('placeholder')] = $(input).val();
      });

      return formData;
    },

    submitLead : function(e) {
      var formData = this.getFormData();

      if(this.editMode) {
        var model = this.collection.get(this.getFormId());
        
        if(!_.isEmpty(model)) {
          model.save(formData, {
            success: _.bind(this.addSuccessCallback, this)
          });
        }
      }
      else {
        this.collection.create(
          formData, 
          { 
            silent: true, 
            wait  : false, 
            success : _.bind(this.addSuccessCallback, this),
            error   : _.bind(this.addErrorCallback, this) 
          }
        );  
      }

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

    cleanForm : function() {
      $('#lead-add input, #lead-add textarea').not('[type=submit]').val('');
    },

    addSuccessCallback : function(collection, response) {
      this.cleanErrors();
      this.cleanForm();

      App.vent.trigger('layout:message', { type: "success", message : 'Lead added!'});
      App.vent.trigger('lead:add');
    },

    cleanErrors : function() {
      $('small.error').remove();
      $('input.error').removeClass('error');
    },

    customRender: function() {
      $.get(this.options.url.api + 'get/form/lead/', _.bind(function(response) {
        response = JSON.parse(response).form;
        this.$el.append(this.getHtml(response));
      }, this));
    },

    getCsfrToken: function() {
      $.get(this.options.url.api + 'get/token/', function(response) {
        this.token = response.token;
      });
    },

    setInputData : function(model, key, input){
        var value = model.get($(input).attr('name'));
        $(input).val(this.formatInputValue(input, value));
    },

    setFormData : function(model) {
      $.each(
        $('#lead-add input, #lead-add textarea').not('[type=submit]'), 
        _.bind(this.setInputData, this, model)
      );
    },

    formatInputValue : function(input, value) {
      if(!_.isEmpty($(input).attr('data-format'))) {
        value = moment(value).calendar();
      }

      return value;
    },

    editMode : function(model) {
      this.editMode = true;

      this.setEditId(model.get('id'));

      this.setFormData(model);
      this.setButtons();
    },

    getEditId : function() {
      return $('#lead-add').attr('data-lead-id');
    },

    setEditId : function(id) {
      $('#lead-add').attr('data-lead-id', (_.isUndefined(id) ? '' : id ));
    },

    setButtons : function() {
      $('#lead-add .button.cancel')[(this.editMode) ? 'show' : 'hide']();
      $('#lead-add .button.submit').val(((this.editMode) ? 'Edit' : 'Create new') + ' LEAD');
    },

    cancelEdit : function() {
      this.editMode = false;

      this.cleanForm();
      this.cleanErrors();

      this.setEditId();

      this.setButtons();

      return false;
    },

    initialize: function(options) {
      console.log('LeadFormView::initialize');
      
      App.vent.on('lead:edit', _.bind(this.editMode, this) );   

      this.options = options;
      this.getCsfrToken();
    }
  };
  
  var LeadFormView = Backbone.View.extend(_.extend(View, new BaseView()));
  
  return LeadFormView;
});