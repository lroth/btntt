//global define
//global Backbone

define([], function () {
    "use strict";

    var Collection = function () {
        this.initialize = function (options) {
            this.url = options.url;
            return this;
        };
    };

    return Collection;
});