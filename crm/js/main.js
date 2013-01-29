requirejs.config({
  baseUrl: 'js',

  paths: {
    'backbone': 'lib/backbone/backbone',
    'backbone.marionette': 'lib/backbone/backbone.marionette',

    'jquery': 'lib/jquery',
    'underscore': 'lib/underscore',
    'handlebars': 'lib/handlebars',
    'text': 'lib/require/text'
  },

  shim: {
    'app': {
      deps: ['backbone.marionette']
    },
    'backbone': {
      deps: ['underscore', 'jquery', 'handlebars'],
      exports: 'Backbone'
    },
    'backbone.marionette': {
      deps: ['backbone'],
      exports: 'Marionette'
    },

    'jquery': {
      exports: '$'
    },
    'underscore': {
      exports: '_'
    },
    'handlebars': {
      exports: 'Handlebars'
    }
  }
});

console.log('Require.js configuration loaded...');

require(['app'], function(app) {
  app.start();
});