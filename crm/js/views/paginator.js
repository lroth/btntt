//global define
//global Backbone
//global Handlebars

define([
    'collections/paginator',
    'text!templates/paginator.html'
],
    function (PaginatedCollection, paginatorTemplate) {
        "use strict";

        var PaginatorMainView = Backbone.View.extend({
            template: Handlebars.compile(paginatorTemplate),

            events: {
                'click a.servernext'    : 'nextResultPage',
                'click a.serverprevious': 'previousResultPage',
                'click a.orderUpdate'   : 'updateSortBy',
                'click a.serverlast'    : 'gotoLast',
                'click a.page'          : 'gotoPage',
                'click a.serverfirst'   : 'gotoFirst',
                'click a.serverpage'    : 'gotoPage',
                'click .serverhowmany a': 'changeCount'

            },

            render: function () {
                var info = this.collection.info();
                console.log('render paginator view with ', info);

                var html = this.template(info);
                this.$el.html(html);
            },

            updateSortBy: function (e) {
                e.preventDefault();
                var currentSort = $('#sortByField').val();
                this.collection.updateOrder(currentSort);
            },

            nextResultPage: function (e) {
                e.preventDefault();
                this.collection.requestNextPage();
            },

            previousResultPage: function (e) {
                e.preventDefault();
                this.collection.requestPreviousPage();
            },

            gotoFirst: function (e) {
                e.preventDefault();
                this.collection.goTo(this.collection.information.firstPage);
            },

            gotoLast: function (e) {
                e.preventDefault();
                this.collection.goTo(this.collection.information.lastPage);
            },

            gotoPage: function (e) {
                e.preventDefault();
                var page = $(e.target).text();
                this.collection.goTo(page);
            },

            changeCount: function (e) {
                e.preventDefault();
                var per = $(e.target).text();
                this.collection.howManyPer(per);
            },

            tagName: 'div',
            id     : 'paginator-content',

            initialize: function (options) {
                console.log('initialize pa//ginator view');
                this.collection = new PaginatedCollection(options);

                this.collection.on('reset', this.render, this);
                this.collection.on('change', this.render, this);

                this.$el.appendTo('#pagination');
            }
        });

        return PaginatorMainView;
    });