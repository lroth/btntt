//global Backbone
//global define

define([
    'text!templates/lead/search.html'
],
    function (tmpl) {
        "use strict";

        var LeadSearchView = Backbone.View.extend({
            tagName  : 'div',
            id       : 'search',
            className: 'eight columns',

            render: function () {
                this.$el.html(tmpl);
            },

            initialize: function (options) {
            }
        });

        return LeadSearchView;
    });