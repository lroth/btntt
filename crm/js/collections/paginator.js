// Generated by CoffeeScript 1.6.2
/* global define, Backbone
*/


(function() {
  "use strict";  define([], function() {
    var Collection, PaginatedCollection;

    Collection = {
      options: null,
      model: null,
      initialize: function(options) {
        console.log('initialized paginator collection with options: ', options);
        this.model = options.collection.model;
        this.paginator_core.url = options.url.rest;
        return this;
      },
      paginator_core: {
        type: "GET",
        dataType: "json",
        url: ""
      },
      paginator_ui: {
        firstPage: 1,
        currentPage: 1,
        pagesInRange: 1,
        perPage: 1,
        totalPages: 1
      },
      server_api: {
        $filter: true,
        $top: function() {
          return this.totalPages * this.perPage;
        },
        $skip: function() {
          return this.totalPages * this.perPage;
        },
        orderby: "id",
        $format: "json"
      },
      parse: function(response) {
        console.log('parse paginator collection response', response);
        this.totalPages = response.details.totalRecords;
        this.perPage = response.details.perPage;
        return response.resources;
      }
    };
    PaginatedCollection = Backbone.Paginator.requestPager.extend(Collection);
    return PaginatedCollection;
  });

}).call(this);