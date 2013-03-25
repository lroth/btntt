//global define
//global Backbone

define(['app'], function (App) {
    "use strict";

    var LayoutMainView = Backbone.View.extend({
        tagName: 'div',
        id     : 'main',

        events: {
        },

        initialize: function (options) {
            App.vent.on('layout:message', this.showMessage);
        },

        showMessage: function (event) {
            $('.alert-box').addClass(event.type).text(event.message).fadeIn();
            setTimeout(function () {
                $('.alert-box').fadeOut();
            }, 4000);
        }
    });

    return LayoutMainView;
});