//global define
//global _
//global Backbone

define([
    'core/list',
    'text!templates/lead/list.html'
],
    function (BaseList, tmpl) {
        "use strict";

        var View = {
            id  : 'leads',
            tmpl: tmpl,

            initialize: function (options) {
                console.log('\r\n LeadListView::initialize');

                this.collection = options.collection;
                this.bindBehaviors();
            }
        };

        var LeadListView = Backbone.View.extend(View);
        _.extend(LeadListView.prototype, new BaseList());

        return LeadListView;
    });