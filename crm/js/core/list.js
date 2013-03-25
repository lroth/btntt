//global define
//global _
//global Pikaday
//global moment
//global Handlebars

define([
    'app',
    'core/view',

    //Oh, god, it should be component
    'text!templates/confirm.popup.html'
],
    function (App, BaseView, confirmPopupTmpl) {
        "use strict";

        var List = function () {
            //@TODO: it's wrong way definitely
            this.confirmPopup = Handlebars.compile(confirmPopupTmpl);

            this.behaviors = {
                'add': { action: 'render' }
            };

            this.tagName = 'div';
            this.className = 'eight columns';

            this.events = {
                'click .remove': 'onResourceRemove',
                'click .edit'  : 'onResourceEdit'
            };

            this.onResourceRemove = function (e) {
                var resource = this.getResourceData(e.target);

                //@TODO: God, forgive me, I'll rewrite it to module ASAP
                var popupId = 'remove-resource-' + resource.model.id;
                var popup = this.confirmPopup({
                    id     : popupId,
                    title  : 'Sure remove?',
                    content: 'Confirm you want to remove this item.',
                    button : {cancel: 'Cancel', submit: 'Sure'}
                });

                $('body').append(popup);
                $('#' + popupId).reveal();
                $('#' + popupId).find('.button.cancel').click(function () {
                    $(this).parent().trigger('reveal:close');
                });
                $('#' + popupId).find('.button.submit').click(_.bind(function () {
                    $('#' + popupId).trigger('reveal:close');
                    this.removeResource(resource);
                }, this));

                return false;
            };

            this.removeResource = function (resource) {
                if (!_.isUndefined(resource.model)) {
                    resource.model.destroy({
                        success: _.bind(this.onResourceRemoved, this, resource)
                    });
                }
            };

            this.onResourceRemoved = function (resource, collection, response) {
                this.removeResourceView(resource.view);
                App.vent.trigger(this.getEventName('remove'), resource.model.id);
                App.vent.trigger('layout:message', { type: "success", message: response.message});
            };

            this.removeResourceView = function (resourceView) {
                $(resourceView).hide('slow', function () {
                    $(resourceView).remove();
                });
            };

            this.onResourceEdit = function (e) {
                App.vent.trigger(this.getEventName('edit'), this.getResourceData(e.target).model);
                return false;
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