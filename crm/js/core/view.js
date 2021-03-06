//global define
//global Handlebars
//global _

define(['app'], function (App) {
    "use strict";

    var View = function () {
        // first render flag, if it's not first render we can
        // execute `render` in other way
        this.isFirstRender = true;

        this.getHtml = function (response) {
            // create handlebar template object
            var tmplCompiled = Handlebars.compile(this.tmpl);

            // return compiled html, by parsing passed json
            return tmplCompiled({
                data: response
            });
        };

        // render view
        this.render = function () {
            // if in child object we have custom render, use it instead
            if (!_.isUndefined(this.customRender)) {
                this.customRender();
            }
            else {
                // fetch data and add new element to DOM
                if (this.isFirstRender) {
                    var jsons = this.collection.toJSON();
                    console.log('View first render response is: ', jsons);
                    this.isFirstRender = false;
                    this.$el.html(this.getHtml(jsons));
                }
                else {
                    // change only `this.$el` html content
                    this.$el.fadeOut();
                    this.$el.html(this.getHtml(this.collection.toJSON()));
                    this.$el.fadeIn();
                }
            }
        };

        this.bindBehaviors = function () {
            var resource = '';

            _.each(this.behaviors, function (event, name) {
                resource = (_.isUndefined(event.resource)) ? this.options.modelName : event.action;
                App.vent.on(resource + ':' + name, _.bind(this[event.action], this));
            }, this);
        };

        this.getEventName = function (name, resource) {
            return ((_.isUndefined(resource)) ? this.options.modelName : resource) + ':' + name;
        };
    };

    return View;
});