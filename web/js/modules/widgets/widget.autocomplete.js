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
                    var url     = 'http://localhost/btntt/web/project/autocomplete?query=' + query;
                    miApp.request({type: 'GET', url: url}, _.bind(process));
                }
            });

            return false;
        },
    }

    widgetFactory.setFactoryClass(Autocomplete);
    return widgetFactory;
});