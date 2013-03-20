//global define
//global Backbone

define([
    'views/enquiry/form',
    'views/enquiry/list',
    'views/enquiry/search'
],
    function (ViewForm, ViewList, ViewSearch) {
        "use strict";

        var EnquiriesMainView = Backbone.View.extend({
            tagName: 'div',
            id     : 'enquiries',

            // render main view
            render : function () {

                // remove html content from main view
                this.$el.empty();
                this.renderSubViews();
            },

            renderSubViews: function () {
                _.each(this.subViews, this.renderSubView, this);
            },

            // add to DOM html content of every subview
            renderSubView : function (view) {

                // append `this.$el` which representing `view` to main view `$el`
                this.$el.append(view.$el);

                // render passed `view`
                view.render();
            },

            initialize: function (options) {

                // subviews will be appened in same order as defined in `this.subViews` objects
                this.subViews = {
                    form: new ViewForm(options),
//                    search: new ViewSearch(options),
                    list: new ViewList(options)
                };
            }
        });

        return EnquiriesMainView;
    });