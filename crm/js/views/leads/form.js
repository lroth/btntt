//global define
//global _
//global Backbone

define([
    'App',
    'core/form',
    'text!templates/lead/form.html'
], function (App, BaseForm, tmpl) {
    "use strict";

    var View = {
        resourceName: 'lead',
        tmpl        : tmpl,

        initialize: function (options) {
            console.log('LeadFormView::initialize');

            this.options = options;
            this.getCsfrToken();

            this.bindBehaviors();
        }
    };

    var LeadFormView = Backbone.View.extend(View);
    _.extend(LeadFormView.prototype, new BaseForm());

    return LeadFormView;
});