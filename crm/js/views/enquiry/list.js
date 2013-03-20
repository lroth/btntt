//global define
//global _
//global Backbone

define([
    'core/list',
    'text!templates/enquiry/list.html'
],
    function (BaseList, listTmpl) {
        "use strict";

        var View = {
            id  : 'enquiries',
            tmpl: listTmpl,

            initialize: function (options) {
                this.collection = options.collection;
                this.bindBehaviors();
            }
        };

        var EnquiryListView = Backbone.View.extend(View);
        _.extend(EnquiryListView.prototype, new BaseList());

        return EnquiryListView;
    });