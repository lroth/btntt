// global Backbone
// global Marionette
// global define
// global _

define(['router'], function (router) {
    "use strict";

    var App = new Marionette.Application();

    App.url = {
        api : '/web/api/',
        rest: '/web/rest/'
    };

    App.initializeLayout = function () {
        // Initialize layout view
        require(['views/layout/main'], _.bind(function (LayoutView) {
            this.layoutView = new LayoutView();
        }, this));
    };

    // prepend host to every url from `App.url`
    App.setUrls = function () {
        var origin = Backbone.history.location.origin;
        _.each(this.url, function (value, key) {
            this.url[key] = origin + value;
        }, this);
    };

    // helper for accessing url's
    App.getUrl = function (type, route) {
        return this.url[type] + route;
    };

    App.initApp = function () {
        // non sense method a little bit but temporary I can laeve it here
        $.get(this.url.api + 'get/init-data/', _.bind(this.onInitData, this));
    };

    App.onInitData = function (response) {
        var initData = JSON.parse(response);

        if (!initData.user.auth) {
            // redirect to login page if not logged
            window.location = initData.baseUrl + '/login';
        }
        else {
            // set user global data
            this.userData = initData.user;

            // update defined urls with root
            this.setUrls();

            // Initialize whole routing here
            router.initialize();
        }
    };

    // Add layout regions here to controll views
    App.addRegions({
        content  : '#content',
        paginator: '#paginator'
    });

    App.addInitializer(function () {
        this.initApp();
    });

    return App;
});