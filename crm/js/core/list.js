//global define
//global _
//global Pikaday
//global moment

define(['App', 'core/view'], function (App, BaseView) {
    "use strict";

    var List = function () {
        this.behaviors = {
            'add': 'render'
        };

        this.tagName = 'div';
        this.className = 'eight columns';

        this.events = {
            'click .remove': 'removeResource',
            'click .edit'  : 'editResource'
        };

        this.removeResource = function (e) {
            var resource = this.getResourceData(e.target);

            if (!_.isUndefined(resource.model)) {
                resource.model.destroy({
                    success: _.bind(this.onResourceRemoved, this, resource.view)
                });
            }
        };

        this.onResourceRemoved = function (resourceView, collection, response) {
            this.removeResourceView(resourceView);
            App.vent.trigger('layout:message', { type: "success", message: response.message});
        };

        this.removeResourceView = function (resourceView) {
            $(resourceView).hide('slow', function () {
                $(resourceView).remove();
            });
        },

            this.editResource = function (e) {
                App.vent.trigger(this.options.modelName + ':edit', this.getResourceData(e.target).model);
            };

        this.getResourceData = function (target) {
            var resource = {};
            resource.view = $(target).closest('tr');
            resource.id = $(resource.view).attr('data-model-id');
            resource.model = this.collection.get(resource.id);

            return resource;
        };
    };

    // extend this `object.prototype` instead of object only
    // with new base view object (because `BaseView` is `constructor`)
    _.extend(List.prototype, new BaseView());

    return List;
});