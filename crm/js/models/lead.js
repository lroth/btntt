//global Backbone
//global define

define([], function () {
    "use strict";

    var LeadModel = Backbone.Model.extend({
        defaults: {
            alert      : '',
            description: "",
            email      : "",
            name       : ""
        }
    });

    return LeadModel;
});