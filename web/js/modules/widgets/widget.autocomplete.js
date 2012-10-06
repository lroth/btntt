define('widgets/widget.autocomplete', ['bootstrap-typeahead', 'widgets/widget'], function (typeahead, widgetFactory) {
    function Autocomplete(){

    }

    Autocomplete.prototype = {
        init : function(el) {
            this.target = el;

            this.setAutocompleteSelector();
            this.activate();
        },

        setAutocompleteSelector : function() {
            this.selector  = '.autocomplete';
        },

        activate : function() {
            $(this.selector).typeahead({
                source: function(query, process) {
                    miApp.request({type: 'GET', url: this.$element.attr('data-source')}, _.bind(process));
                }
            });

            return false;
        },
    }

    widgetFactory.setFactoryClass(Autocomplete);
    return widgetFactory;
});