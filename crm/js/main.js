requirejs.config({
  baseUrl: 'js',
  urlArgs: "bust=" + +new Date(),

  paths: {
    'backbone': 'lib/backbone/backbone',
    'backbone.marionette': 'lib/backbone/backbone.marionette',

    'jquery': 'lib/jquery',
    'underscore': 'lib/underscore',

    'moment' : 'lib/moment.min',
    
    'handlebars': 'lib/handlebars/handlebars',
    'handlebars.module': 'lib/handlebars/handlebars.module',


    'text': 'lib/require/text'
  },

  shim: {
    'app': {
      deps: ['backbone.marionette']
    },
    'backbone': {
      deps: ['underscore', 'jquery', 'moment', 'handlebars.module'],
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
    },

    'moment' : {
      exports : 'moment'
    }
  }
});

console.log('LOAD: require.js configuration\r\n');

require(['app'], function(app) {
  app.start();
  app.initializeLayout();
});