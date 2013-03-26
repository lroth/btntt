//global define
//global Backbone

define(['core/collection', 'models/enquiry'], function (BaseCollection, EnquiryModel) {
    "use strict";

    var collection = {
        model: EnquiryModel
    };

    var EnquiryCollection = Backbone.Collection.extend(collection);
    _.extend(EnquiryCollection.prototype, new BaseCollection());

    return EnquiryCollection;
});