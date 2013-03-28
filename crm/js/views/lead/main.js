//global define
//global Backbone

define([
    'views/lead/form',
    'views/lead/list',
    'views/lead/search'
],
    function (ViewForm, ViewList, ViewSearch) {
        "use strict";

        var LeadsMainView = Backbone.View.extend({
            tagName: 'div',
            id     : 'leads',

            // render main view
            render : function () {
                console.log('render lead main view');

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
                console.log('initialize lead main view');
                // subviews will be appened in same order as defined in `this.subViews` objects
                this.subViews = {
                    form: new ViewForm(options),
                    list: new ViewList(options)
                };
            }
        });

        return LeadsMainView;
    });