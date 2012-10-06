define('app', ['app.config'], function(config) {
    var App = function() {};

    App.prototype = {
        _cache     : { selector : {} },
        config     : config,
        bundles    : {},
        templates  : {},
        modals     : {},

        callbacks : {
            loadBundles     : function(bundle, key) { if(bundle.activate) { require([key], App.prototype.setBundle) } },
            requestComplete : function(xhr) {
                var response = ((xhr.responseText) || (App.prototype.config.request.httpCode[xhr.status]));
                if(App.prototype.config.consoleDebug) { App.prototype.log(response, 'Request response'); }
            }
        },

        extend : function(Parent, Child) {
            Child.prototype             = new Parent();
            Child.prototype.constructor = Child;
            Child.prototype.parent      = Parent.prototype;
            return Child;
        },

        setModal : function(id, modal) {
            App.prototype.modals[id] = modal;
        },

        getModal : function(id) {
            return (App.prototype.modals[id] || null);
        },

        baseUrl    : function() {
            return App.prototype.config.basePath;
        },

        setBundle : function(bundle) {
            App.prototype.bundles[bundle.name] = App.prototype.initBundle(bundle);
        },

        loadBundles : function() {
            var list = [];
            _.each(App.prototype.config.bundles, App.prototype.callbacks.loadBundles);
        },

        initBundle : function(bundle) {
            var callback = function() {
                bundle.init();
                App.prototype.bindBehaviors(bundle);
                return bundle;
            };

            callback = _.bind(callback, bundle);

            if(_.isArray(bundle.templates)) {
                App.prototype.loadTemplates(bundle.name, bundle.templates, callback);
            }
            return bundle;
        },

        initViews : function() {
            var viewEl = $('.view[data-view]');
            if(viewEl.length > 0) { require(['views/view.' + viewEl.attr('data-view')], function(view) { view.init(viewEl); }); }
        },

        initWidgets : function() {
            var iterFunc = function(el) {
                require( ['widgets/widget.' + $(el).attr('data-widget')], function(widgetFactory) {
                        var w = widgetFactory.createNew();
                        App.prototype.loadTemplates(w.name, w.templates, function() { w.init(el); })
                });
            };
            _.each($('[data-widget]'), iterFunc);
        },

        loadTemplates : function(moduleName, templates, callback) {
            //TODO: if template exist, don't load
            var mapFunction   = function(name) { return modulePath + name + '.' + App.prototype.config.templatesExt };
            var modulePath    = 'text!' + App.prototype.config.templatesPath + '/' + moduleName + '/';
            var originalNames = templates;

            templates = _.map(templates, mapFunction);

            require(templates, function() {
                App.prototype.setTemplates(moduleName, originalNames, arguments);
                return (_.isFunction(callback)) ? callback() : null;
            });
        },

        setTemplates : function(moduleName, templateNames, templates) {
            App.prototype.templates[moduleName] = {};
            for (var i = 0; i < templates.length; i++) { App.prototype.templates[moduleName][templateNames[i]] = templates[i]; }
        },

        getTemplate : function(moduleName, templateName) {
            return ((App.prototype.templates[moduleName] && App.prototype.templates[moduleName][templateName]) || null);
//            var template = App.prototype.templates[moduleName][templateName];
//            return (typeof template !== "undefined") ? template : null;
        },

        bindBehaviors : function(module) {
            _.each(module.behaviors, function(behavior) { behavior.apply(this); });
        },

        cacheSelector : function(name, selector) {
            if(typeof selector !== "undefined") { App.prototype._cache.selector[name] = selector; }
            return (App.prototype._cache.selector[name]) ? App.prototype._cache.selector[name] : null;
        },

        request : function(config, callback) {
            if(config && (config.url.indexOf('http') === -1 &&  config.url.indexOf('https') === -1)) {
                config.url = App.prototype.baseUrl() + config.url;
            }

            config          = _.extend(config, App.prototype.config.request);
            config.complete = App.prototype.callbacks.requestComplete;

            App.prototype.log(config.url, 'App::request.config.url');
            $.ajax(config).done(function(response) { if(_.isFunction(callback)) { callback(response); } else { return response } });
        },

        log : function(content, title) {
            //TODO: (new Error).lineNumber
            if(App.prototype.config.consoleDebug) {
                console.log(title);
                console.log(content);
                console.log("\r\n");
            };
        },

        init : function() {
            App.prototype.request({ type : "GET", url : 'profile/user_loggedin'}, function(response) {
                    if(response > 0) {
                        App.prototype.config.userId = response;

                        App.prototype.initViews();
                        App.prototype.initWidgets();

                        App.prototype.loadBundles();
                    }
                });
            }
        };

    return App;
});