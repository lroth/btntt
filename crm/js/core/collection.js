//global define
//global Backbone
//global _

define([], function () {
    "use strict";

    var Collection = function () {
        this.initialize = function (options) {
            this.url = options.url;
            console.log('initialize lead collection with url :', this.url);
            return this;
        };

        this.parse = function (response) {
            response = (_.isUndefined(response.results)) ? response : response.results;
            console.log('parsed collection response ', response);
            return response;
        };
    };

    return Collection;
});