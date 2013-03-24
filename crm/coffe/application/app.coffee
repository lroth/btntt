### global Backbone, Marionette, define, _, Moderniz ###
"use strict"

define ["application/config", "application/router"], (config, router) ->
  App = new Marionette.Application()
  App.url =
    api: "/btntt/web/api/"
    rest: "/btntt/web/rest/"

  App.initializeLayout = ->

    # Initialize layout view
    require ["views/layout/main"], (LayoutView) =>
      @layoutView = new LayoutView()
      return

    return @


  # prepend host to every url from `App.url`
  App.setUrls = ->
    origin = Backbone.history.location.origin
    _.each @url, ((value, key) ->
      @url[key] = origin + value
      return @
    ), this


  # helper for accessing url's
  App.getUrl = (type, route) ->
    @url[type] + route
    return @

  App.initApp = ->

    # non sense method a little bit but temporary I can laeve it here
    $.get @url.api + "get/init-data/", _.bind(@onInitData, this)
    return @

  App.onInitData = (response) ->
    initData = JSON.parse(response)
    unless initData.user.auth

      # redirect to login page if not logged
      window.location = initData.baseUrl + "/login"
    else

      # set user global data
      @userData = initData.user

      # update defined urls with root
      @setUrls()

      # Initialize whole routing here
      router.initialize()


  # Add layout regions here to controll views
  App.addRegions
    content: "#content"
    paginator: "#paginator"

  App.scrollTop = ->
    if Modernizr.touch and not window.location.hash
      $(window).load ->
        setTimeout (->

          # At load, if user hasn't scrolled more than 20px or so...
          window.scrollTo 0, 1  if $(window).scrollTop() < 20
        ), 0


  App.addInitializer ->
    @initApp()
    @scrollTop()

  App
