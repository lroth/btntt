### global define, Backbone ###
"use strict";

define [], ->
  Collection =
    options   : null
    model     : null
    initialize: (options) ->
      console.log('initialized paginator collection with options: ', options)
      @model = options.collection.model
      #@paginator_core.url = options.url.api + 'paginate/' + options.modelName
      #      @paginator_core.url = options.url.rest + options.modelName
      @paginator_core.url = options.url.rest
      return @

    paginator_core:
      type    : "GET"
      dataType: "json"
      url     : ""

    paginator_ui:
      firstPage   : 1
      currentPage : 1
      pagesInRange: 1

      #to change - same as at server should be
      perPage     : 1

      #from server
      totalPages  : 1

    server_api:
    # the query field in the request
      $filter: true

      # number of items to return per request/page
      $top   : ->
        @totalPages * @perPage


      # how many results the request should skip ahead to
      # customize as needed. For the Netflix API, skipping ahead based on
      # page * number of results per page was necessary.
      $skip  : ->
        @totalPages * @perPage


      # field to sort by
      orderby: "id"

      # what format would you like to request results in?
      $format: "json"

    parse: (response) ->
      console.log('parse paginator collection response', response)

      @totalPages = response.details.totalRecords
      @perPage = response.details.perPage
      response.resources

  PaginatedCollection = Backbone.Paginator.requestPager.extend(Collection)
  PaginatedCollection
