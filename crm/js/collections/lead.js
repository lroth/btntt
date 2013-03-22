//global define
//global Backbone

define(['models/lead'], function (LeadModel) {
    "use strict";

    var LeadCollection = Backbone.Collection.extend({
        model: LeadModel,

        initialize: function (options) {
            this.url = options.url;
        }
    });

    return LeadCollection;
});