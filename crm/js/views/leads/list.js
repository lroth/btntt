//global define
//global _
//global Backbone

define([
    'core/list',
    'text!templates/lead/list.html'
],
    function (BaseList, listTmpl) {
        "use strict";

        var View = {
            id  : 'leads',
            tmpl: listTmpl,

            initialize: function (options) {
                this.collection = options.collection;
                this.bindBehaviors();
            }
        };

        var LeadListView = Backbone.View.extend(View);
        _.extend(LeadListView.prototype, new BaseList());

        return LeadListView;
    });