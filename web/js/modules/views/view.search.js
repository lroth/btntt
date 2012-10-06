define('views/view.search', [], function () {
    //TODO:
    //Make scopes objects var view { main: '', table: '' } etc.

    //TODO - merge it to one config
    var sCacheConf  = { labels : ['weight', 'price'], prefs : ['min', 'max'] },
        sCache      = {
                        input: {
                            weightParagraph : $('.slider-weight p span'),
                            priceParagraph  : $('.slider-price p span') }, filters : $('.filters_box')
                      },

        labels     = { price : 'zł', weight : 'g', delimiter : ' - ' }, // <----- TODO - Dependency Injection, from App?
        values     = {
                        range : { minW : 1, minP : 1, maxW : 50, maxP : 300 },
                        def   : { minW : 5, minP : 25, maxW : 35, maxP : 150 }
                     };

    _.each(sCacheConf.labels, function(label, key) {
        _.each(sCacheConf.prefs, function(pref, key) {
            sCache.input[label + _.captFirst(pref)] = $('[name=' + label + '-' + pref + ']');
        });
    });

    var updateParagraph = function(type, min, max) {
        sCache.input[type + 'Paragraph'].text(min + labels.delimiter + max + labels[type]);
    };

    var updateParagraphs = function() {
        updateParagraph('price', values.def.minP, values.def.maxP);
        updateParagraph('weight', values.def.minW, values.def.maxW);
    }

    //TODO: make magic here
    /**
     * WHOOOAAAAAAA THIS SHOULD BE ONE HUGE MAGIC!
     * AFTER CHAT I'LL MADE SOME CHANGES HERE BECAUSE THIS IS BAAADZZZZ :(
     */
    var addSliderPrice = function() {
        $( ".slider-price .slider" ).slider({
            range: true,
            min: values.range.minP,
            max: values.range.maxP,
            values: [ values.def.minP, values.def.maxP ],
            stop: function( event, ui ) {
                sCache.input.priceMin.val(ui.values[0]);
                sCache.input.priceMax.val(ui.values[1]);

                updateParagraph('price', ui.values[0], ui.values[1]);
            }
        });
    };

    var addSliderWeight = function() {
        $( ".slider-weight .slider" ).slider({
            range: true,
            min: values.range.minW,
            max: values.range.maxW,
            values: [ values.def.minW, values.def.maxW ],
            stop: function( event, ui ) {
                sCache.input.weightMin.val(ui.values[0]);
                sCache.input.weightMax.val(ui.values[1]);

                updateParagraph('weight', ui.values[0], ui.values[1]);
            }
        });
    };

    var addBehaviours = function() {
        $('.btn_select').click(function(){
            if($(this).hasClass('btn_select_active')) {
                $(this).removeClass('btn_select_active');
                sCache.filters.toggle();
            }
            else {
                $(this).addClass('btn_select_active');
                sCache.filters.toggle();
            }
        });
    };

    return {
        init : function () {
            updateParagraphs();

            addBehaviours();
            addSliderPrice();
            addSliderWeight();
        }};
});