//global define
//global _
//global Backbone

define([
    'App',
    'core/form',
    'text!templates/enquiry/form.html'
], function (App, BaseForm, tmpl) {
    "use strict";

    var View = {
        resourceName: 'enquiry',
        tmpl        : tmpl,

        initialize: function (options) {
            this.options = options;
            this.getCsfrToken();

            this.bindBehaviors();
        }
    };

    var EnquiryFormView = Backbone.View.extend(View);
    _.extend(EnquiryFormView.prototype, new BaseForm());

    return EnquiryFormView;
});