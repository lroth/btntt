//global define
//global Backbone

define([
    'collections/paginator'
],
    function (PaginatedCollection) {
        "use strict";

        var PaginatorMainView = Backbone.View.extend({
            tagName: 'div',
            id     : 'paginator-content',

            render: function () {
                this.$el.empty();
            },

            initialize: function (options) {
                this.collection = new PaginatedCollection(options);
                var response = this.collection.goTo(1);
                setTimeout(function () {
                    console.log(response.responseText);
                }, 1000);
            }
        });

        return PaginatorMainView;
    });