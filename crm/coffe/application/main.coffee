### global requirejs ###
"use strict"

requirejs.config
  baseUrl: "js"
  urlArgs: "bust=" + (+new Date())
  paths  :
    'app'                : 'application/app'
    'backbone'           : 'lib/backbone/backbone.min'
    'backbone.marionette': 'lib/backbone/backbone.marionette'
    'backbone.paginator' : 'lib/backbone/backbone.paginator.min'
    'jquery'             : 'lib/jquery/jquery'

    #@TODO: move this to external module
    "jquery.reveal"      : "lib/foundation/jquery.foundation.reveal"
    "jquery.module"      : "lib/jquery/jquery.module"
    parsley              : "lib/jquery/parsley"
    modernizr            : "lib/foundation/modernizr.foundation"
    pikaday              : "lib/pikaday"
    underscore           : "lib/underscore"
    moment               : "lib/moment.min"
    handlebars           : "lib/handlebars/handlebars"
    "handlebars.module"  : "lib/handlebars/handlebars.module"
    text                 : "lib/require/text"

  shim:
    "app":
      deps: ["backbone.marionette", "backbone.paginator", "modernizr"]

    backbone:
      deps   : ["underscore", "jquery.module", "handlebars.module", "moment", "pikaday", "parsley"]
      exports: "Backbone"

    "backbone.paginator":
      deps: ["backbone"]

    "backbone.marionette":
      deps   : ["backbone"]
      exports: "Marionette"

    modernizr:
      exports: "Modernizr"

    "jquery.module":
      deps: ["jquery"]

    parsley:
      deps: ["jquery"]

    jquery:
      exports: "$"

    pikaday:
      deps: ["moment"]

    underscore:
      exports: "_"

    handlebars:
      exports: "Handlebars"

    moment:
      exports: "moment"

require ["app"], (app) ->
  app.start()
  app.initializeLayout()
  return @