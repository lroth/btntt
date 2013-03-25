//global define
//global _
//global Pikaday
//global moment

define(['app', 'core/view'], function (App, BaseView) {
    "use strict";

    var Form = function () {
        this.behaviors = {
            'edit'  : { action: 'setEditMode' },
            'remove': { action: 'onResourceRemove' }
        };

        //backbone view stuff
        this.tagName = 'div';
        this.className = 'four columns';
        this.id = 'model-form';

        this.events = {
            'click input.save'  : 'saveModel',
            'click input.cancel': 'resetForm'
        };

        // contains data pickers for easy access
        this.pickers = {};

        // define if form is in edit mode or not
        this.isEditMode = false;

        // selectors cache
        this.elements = {};

        // cache selectors in `this.elements` object
        this.setElementsSelectors = function () {
            this.elements.form = $('#model-save');
            this.elements.inputs = $(this.elements.form).find('input, textarea, select').not('[type=submit]');

            this.elements.cancelBtn = $(this.elements.form).find('.button.cancel');
            this.elements.successBtn = $(this.elements.form).find('.button.success');
        };

        // get values from each elements specified in `this.elements.inputs`
        this.getFormData = function () {
            var formData = {};

            $.each(this.elements.inputs, function (key, input) {
                formData[$(input).attr('name')] = $(input).val();
            });

            return formData;
        };

        // can create new and edit existing model
        this.saveModel = function (e) {
            e.preventDefault();

            var formData = this.getFormData();

            var modelSaveOptions = {
                // wait for the server before adding the new model to the collection
                wait : true,

                // when passing `path` to model options during save
                // only changed fields will be saved
                patch: this.isEditMode,

                success: _.bind(this.saveSuccessCallback, this),
                error  : _.bind(this.saveErrorCallback, this)
            };

            // @TODO: merge to one thing
            if (this.isEditMode) {
                var model = this.collection.get(this.getEditId());

                if (!_.isEmpty(model)) {
                    model.save(formData, modelSaveOptions);

                }
            } else {
                this.collection.create(formData, modelSaveOptions);
            }

            return false;
        };

        // handling model save errors
        this.saveErrorCallback = function (collection, response) {
            var objResponse = JSON.parse(response.responseText);

            // remove old errors and show new
            if (!_.isUndefined(objResponse.errors)) {
                this.cleanErrors();
                this.showErrors(objResponse.errors);
            }
        };

        // parse json error list to template
        this.showErrors = function (errors) {
            //@TODO: use templates here!
            _.each(errors, function (value, key) {
                var attr = '[name=' + key + ']',
                    small = '<small style="display:none;" class="error">' + value + '</small>';

                $(this.elements.inputs).filter(attr).addClass('error').after(small);
                $(this.elements.form).find('small.error').fadeIn('normal');
            }, this);
        };

        // remove all values
        this.cleanForm = function () {
            //@TODO: add selects reset handling
            $(this.elements.inputs).val('');
        };

        // handling successfull model save
        this.saveSuccessCallback = function (collection, response) {
            // clean form from just added values
            this.resetForm();

            // @TODO: just make this better
            App.vent.trigger('layout:message', {
                type   : "success",
                message: this.options.modelName + ' ' + ((this.isEditMode) ? 'edited' : 'added') + '!'
            });

            // Tell other views, that new model is added
            // (for example list.js want to refresh when new lead is added)
            App.vent.trigger(this.getEventName('add'));
        };

        // cleaning form from css error classes
        this.cleanErrors = function () {
            $(this.elements.form).find('small.error').remove();
            $(this.elements.inputs).filter('.error').removeClass('error');
        };

        // fired from `render()` method when it's defined
        this.customRender = function () {
            // grab form in json format
            $.get(this.options.url.api + 'get/form/' + this.options.modelName, _.bind(function (response) {
                response = response.form;

                // generated html from json form
                this.$el.append(this.getHtml(response));

                // cache form selectors
                this.setElementsSelectors();

                // initialize custom handlings on some fields
                this.initializeCustomFields();
            }, this));
        };

        this.initializeCustomFields = function () {

            // initialize pikaday for every data
            $.each($('[data-format=datetime]'), function (key, value) {
                this.pickers[$(value).attr('name')] = new Pikaday({
                    firstDay: 1,
                    field   : value,
                    minDate : moment().toDate(),
                    format  : 'YYYY-MM-DD'
                });
            }.bind(this));
        };

        // actually unused
        this.getCsfrToken = function () {
            console.log(this.options);
            $.get(this.options.url.api + 'get/token/', function (response) {
                this.token = response.token;
            });
        };

        // propagate model values to inputs with same names in edit mode
        this.setInputData = function (model, key, input) {
            //@TODO: again, select isn't handled
            var value = model.get($(input).attr('name'));
            $(input).val(this.formatInputValue(input, value));
        };

        // just fire setInputData for each element
        this.setFormData = function (model) {
            $.each($(this.elements.inputs), _.bind(this.setInputData, this, model));
        };

        // handle custom `data-format` for inputs
        this.formatInputValue = function (input, value) {
            // value will be parsed by `moment.js`
            switch ($(input).attr('data-format')) {
                //@TODO: put this to external class
                case 'datetime' :
                    //@TODO: set date format in some global config
                    value = moment(value).format('YYYY-MM-DD');
                    break;
            }

            return value;
        };

        this.onResourceRemove = function (modelId) {
            if (this.isEditMode && (this.editId === modelId)) {
                this.resetForm();
            }
        };

        // change form mode to edit
        this.setEditMode = function (model) {
            // set correct flag
            this.isEditMode = true;

            // remove old validation errors
            this.cleanErrors();

            // set id of model we are editing
            this.setEditId(model.get('id'));

            // propagate model data to form
            this.setFormData(model);

            // change button captions and add cancel button
            this.setButtons();
        };

        // helper for quick model id access
        this.getEditId = function () {
            return $(this.elements.form).attr('data-model-id');
        };

        // helper for quick mode id set
        this.setEditId = function (id) {
            this.editId = id;
            $(this.elements.form).attr('data-model-id', (_.isUndefined(id) ? '' : id));
        };

        // set buttons visibillity and captions
        this.setButtons = function () {
            var caption = ((this.isEditMode) ? 'Edit' : 'Create new') + ' ' + this.options.modelName.toUpperCase();

            // show cancel button
            $(this.elements.cancelBtn)[(this.isEditMode) ? 'show' : 'hide']();

            // change universal save button caption
            $(this.elements.successBtn).val(caption);
        };

        // revert this form class and form view to basic state
        this.resetForm = function () {
            this.isEditMode = false;

            this.cleanForm();
            this.cleanErrors();

            this.setEditId();
            this.setButtons();

            return false;
        };
    };

    // extend this `object.prototype` instead of object only
    // with new base view object (because `BaseView` is `constructor`)
    _.extend(Form.prototype, new BaseView());

    return Form;
});