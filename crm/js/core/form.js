define(['App', 'core/view'], function(App, BaseView) {

  var Form = function() {
      /* backbone view stuff */
      this.tagName    = 'div';
      this.className  = 'four columns';
      this.id         = 'model-form';

      this.events = {
        'click input.save'  : 'saveModel',
        'click input.cancel': 'resetForm'
      };

      this.isEditMode = false;

      /* selectors cache */
      this.elements   = {};

      this.setElementsSelectors = function() {
        this.elements.form    = $('#model-save');
        this.elements.inputs  = $(this.elements.form).find('input, textarea').not('[type=submit]');

        this.elements.cancelBtn   = $(this.elements.form).find('.button.cancel');
        this.elements.successBtn  = $(this.elements.form).find('.button.success');
      };

      this.getFormData = function() {
        var formData = {};

        $.each(this.elements.inputs, function(key, input) {
          formData[$(input).attr('name')] = $(input).val();
        });

        return formData;
      };

      this.saveModel = function(e) {
        e.preventDefault();

        var formData = this.getFormData();

        this.modelSaveOptions.success = _.bind(this.modelSaveOptions.success, this);
        this.modelSaveOptions.error   = _.bind(this.modelSaveOptions.error, this);

        /* @todo: merge to one thing */
        if(this.isEditMode) {
          var model = this.collection.get(this.getEditId());
          
          if(!_.isEmpty(model)) {
            this.modelSaveOptions.patch = true;
            model.save(formData, this.modelSaveOptions);
            
          }
        } else {
          this.modelSaveOptions.patch = false;
          this.collection.create(formData, this.modelSaveOptions);
        }

        return false;
      };

      this.addErrorCallback = function(collection, response) { console.log('foo');
        var objResponse = JSON.parse(response.responseText);

        if(!_.isUndefined(objResponse.errors)) {
          this.cleanErrors();
          this.showErrors(objResponse.errors);
        }
      };

      this.showErrors = function(errors) {
        //@TODO: use templates here!
        for(var i in errors) {
          var attr = '[name=' + i + ']',
            small = '<small style="display:none;" class="error">' + errors[i] + '</small>';

          $(this.elements.inputs).filter(attr).addClass('error').after(small);
          $(this.elements.form).find('small.error').fadeIn('normal');
        }
      };

      this.cleanForm = function() {
        $(this.elements.inputs).val('');
      },

      this.addSuccessCallback = function(collection, response) {
        this.resetForm();

        /* @TODO: just make this better  */
        App.vent.trigger('layout:message', {
          type: "success",
          message: 'Lead ' + (this.isEditMode) ? 'edited' : 'added' +'!'
        });

        App.vent.trigger('lead:add');
      },

      this.cleanErrors = function() {
        $(this.elements.form).find('small.error').remove();
        $(this.elements.inputs).filter('.error').removeClass('error');
      },

      this.customRender = function() {
        $.get(this.options.url.api + 'get/form/' + this.options.modelName, _.bind(function(response) {
          response = response.form;
          this.$el.append(this.getHtml(response));
          this.setElementsSelectors();
        }, this));
      },

      this.getCsfrToken = function() {
        $.get(this.options.url.api + 'get/token/', function(response) {
          this.token = response.token;
        });
      },

      this.setInputData = function(model, key, input) {
        var value = model.get($(input).attr('name'));
        $(input).val(this.formatInputValue(input, value));
      },

      this.setFormData = function(model) {
        $.each($(this.elements.inputs), _.bind(this.setInputData, this, model));
      },

      this.formatInputValue = function(input, value) {
        if(!_.isEmpty($(input).attr('data-format'))) {
          value = moment(value).calendar();
        }

        return value;
      },

      this.setEditMode = function(model) {
        this.isEditMode = true;

        this.cleanErrors();
        this.setEditId(model.get('id'));

        this.setFormData(model);
        this.setButtons();
      },

      this.getEditId = function() {
        return $(this.elements.form).attr('data-model-id');
      },

      this.setEditId = function(id) {
        $(this.elements.form).attr('data-model-id', (_.isUndefined(id) ? '' : id));
      },

      this.setButtons = function() { console.trace();
        var caption = ((this.isEditMode) ? 'Edit' : 'Create new') + ' ' + this.options.modelName.toUpperCase();

        $(this.elements.cancelBtn)[(this.isEditMode) ? 'show' : 'hide']();
        $(this.elements.successBtn).val(caption);
      },

      this.resetForm = function() {
        this.isEditMode = false;

        this.cleanForm();
        this.cleanErrors();

        this.setEditId();
        this.setButtons();

        return false;
      }

      /* 
       * some configuration for POST and PUT 
       * at the end, when things are defined

       http://backbonejs.org/#Events-catalog USE add, sync instead
      */
      this.modelSaveOptions = {
        silent  : true,
        wait    : true,
        success : this.addSuccessCallback,
        error   : this.addErrorCallback
      };
    };

  _.extend(Form.prototype, new BaseView());

  return Form;
});