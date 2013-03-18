//global define
//global Backbone

define([
    'collections/lead',

    'views/leads/form',
    'views/leads/list',
    'views/leads/search'
],
    function (LeadCollection, ViewForm, ViewList, ViewSearch) {
        "use strict";

        var LeadsMainView = Backbone.View.extend({
            tagName: 'div',
            id     : 'leads',

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
                this.collection = new LeadCollection(options);

                // pass created collection to every subviews
                options.collection = this.collection;

                // subviews will be appened in same order as defined in `this.subViews` objects
                this.subViews = {
                    form  : new ViewForm(options),
                    search: new ViewSearch(options),
                    list  : new ViewList(options)
                };
            }
        });

        return LeadsMainView;
    });