//global define
//global _
//global Pikaday
//global moment
//global Handlebars

define([
    'app',
    'views/paginator'
],
    function (App, PaginatorView) {
        "use strict";

        var Controller = function () {

            this.initialize = function (options) {
                this.setUrl();
                this.setCollection();
                this.setOptions();
            };

            this.setUrl = function () {
                this.url = {
                    api : App.getUrl('api', ''),
                    rest: App.getUrl('rest', this.modelName)
                };
            };

            this.setCollection = function () {
                require(['collections/' + this.modelName], _.bind(this.onCollectionRequire, this));
            };

            this.onCollectionRequire = function (ResourceCollection) {
                this.collection = new ResourceCollection({ url: this.url.rest });
                this.setOptions();
            };

            this.setOptions = function () {
                this.options = {
                    url       : this.url,
                    modelName : this.modelName,
                    collection: this.collection
                };
            };

            this.show = function () {
                require(['views/' + this.modelName + '/main'], _.bind(this.onShowRequire, this));
            };

            this.onShowRequire = function (ResourceMainView) {
                this.collection.fetch({
                    success: _.bind(function (collection, response) {
                        var paginatorView = new PaginatorView(this.options);
                        var resourceMainView = new ResourceMainView(this.options);

                        App.paginator.show(paginatorView);
                        App.content.show(resourceMainView);
                    }, this)
                });
            };
        };

        return Controller;
    });