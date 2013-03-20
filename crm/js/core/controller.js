//global define
//global _
//global Pikaday
//global moment
//global Handlebars

define([
    'App'
],
    function (App) {
        "use strict";

        var Controller = function () {
            this.initialize = function (options) {
                this.setOptions();
            };

            this.setOptions = function () {
                this.options = {
                    url: {
                        api : App.getUrl('api', ''),
                        rest: App.getUrl('rest', this.modelName)
                    },

                    modelName: this.modelName
                };
            };

            this.show = function () {
                require(['views/' + this.modelName + '/main'], _.bind(this.onShowRequire, this));
            };

            this.onShowRequire = function (ResourceMainView) {
                App.content.show(new ResourceMainView(this.options));
            };
        };

        return Controller;
    });