//global define
//global Backbone

define(['models/enquiry'], function (EnquiryModel) {
    "use strict";

    var EnquiryCollection = Backbone.Collection.extend({
        model: EnquiryModel,

        initialize: function (options) {
            this.url = options.url.rest;
        }
    });

    return EnquiryCollection;
});