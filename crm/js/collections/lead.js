//global define
//global Backbone

define(['core/collection', 'models/lead'], function (BaseCollection, LeadModel) {
    "use strict";

    var collection = {
        model: LeadModel
    };

    var LeadCollection = Backbone.Collection.extend(collection);
    _.extend(LeadCollection.prototype, new BaseCollection());

    return LeadCollection;
});