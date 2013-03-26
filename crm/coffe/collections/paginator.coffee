### global define, Backbone ###
"use strict";

define [], ->
  Collection =
    options   : null
    model     : null
    initialize: (options) ->
      @model = options.collection.model
      @paginator_core.url = options.url.api + 'paginate/' + options.modelName
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
      console.log(response)
      @totalPages = response.total
      @perPage = response.perPage
      response

  PaginatedCollection = Backbone.Paginator.requestPager.extend(Collection)
  PaginatedCollection
