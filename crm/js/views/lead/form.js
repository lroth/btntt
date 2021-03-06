//global define
//global _
//global Backbone

define([
    'app',
    'core/form',
    'text!templates/lead/form.html'
], function (App, BaseForm, tmpl) {
    "use strict";

    var View = {
        resourceName: 'lead',
        tmpl        : tmpl,

        initialize: function (options) {
            this.options = options;
            this.getCsfrToken();

            this.bindBehaviors();
        }
    };

    var LeadFormView = Backbone.View.extend(View);
    _.extend(LeadFormView.prototype, new BaseForm());

    return LeadFormView;
});