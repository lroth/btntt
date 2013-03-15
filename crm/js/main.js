//global requirejs

requirejs.config({
    baseUrl: 'js',
    urlArgs: "bust=" + (+new Date()),

    paths: {
        'backbone'           : 'lib/backbone/backbone',
        'backbone.marionette': 'lib/backbone/backbone.marionette',

        'jquery'       : 'lib/jquery/jquery',
        'jquery.module': 'lib/jquery/jquery.module',

        'pikaday'   : 'lib/pikaday',
        'underscore': 'lib/underscore',
        'moment'    : 'lib/moment.min',

        'handlebars'       : 'lib/handlebars/handlebars',
        'handlebars.module': 'lib/handlebars/handlebars.module',

        'text': 'lib/require/text'
    },

    shim: {
        'app'                : {
            deps: ['backbone.marionette']
        },
        'backbone'           : {
            deps   : ['underscore', 'jquery.module', 'handlebars.module', 'moment', 'pikaday'],
            exports: 'Backbone'
        },
        'backbone.marionette': {
            deps   : ['backbone'],
            exports: 'Marionette'
        },

        'jquery'    : {
            exports: '$'
        },
        'pikaday'   : {
            deps: ['moment']
        },
        'underscore': {
            exports: '_'
        },
        'handlebars': {
            exports: 'Handlebars'
        },

        'moment': {
            exports: 'moment'
        }
    }
});

console.log('LOAD: require.js configuration\r\n');

require(['app'], function (app) {
    "use strict";

    app.start();
    app.initializeLayout();
});