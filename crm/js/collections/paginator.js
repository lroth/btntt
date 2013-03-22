//global define
//global Backbone

define([], function () {
    "use strict";

    var PaginatedCollection = Backbone.Paginator.requestPager.extend({
        options: null,
        model  : null,

        initialize: function (collection) {
            this.model = collection.model;
            this.paginator_core.url = collection.url;
        },

        paginator_core: {
            type    : 'GET',
            dataType: 'json',
            url     : ''
        },

        paginator_ui: {
            firstPage   : 1,
            currentPage : 1,
            perPage     : 3,
            totalPages  : 10,
            pagesInRange: 3
        },

        server_api: {
            // the query field in the request
            '$filter'     : true,

            // number of items to return per request/page
            '$top'        : function () {
                return this.totalPages * this.perPage;
            },

            // how many results the request should skip ahead to
            // customize as needed. For the Netflix API, skipping ahead based on
            // page * number of results per page was necessary.
            '$skip'       : function () {
                return this.totalPages * this.perPage;
            },

            // field to sort by
            'orderby'     : 'id',

            // what format would you like to request results in?
            '$format'     : 'json'
        },

        parse: function (response) {
            return response;
        }

    });

    return PaginatedCollection;
});