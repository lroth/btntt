define('widgets/widget', [], function () {
    function WidgetFactory() {
        this.factoryClass = null;
    };

    WidgetFactory.prototype = {
        setFactoryClass : function(factoryClass) {
            this.factoryClass = factoryClass;
        },

        createNew : function() {
            var widget      = new this.factoryClass;
            var widgetApi   = { init : _.bind(widget.init, widget) };

            return widgetApi;
        }
    };

    return new WidgetFactory;
});