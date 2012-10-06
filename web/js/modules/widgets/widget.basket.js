define('widgets/widget.basket', [], function () {
    var basket = {
        target   : null,
        products : null,
        html     : {
            basket : null,
            desc   : null
        }
    };
    basket.update = function () {
//        miApp.request({ url : '' }, basket.render);
        var mock = {products:[{name:'Foo',quantity:'21',price:'21'},{name:'FooBar',quantity:'12',price:'12'},{name:'FooBar',quantity:'4',price:'4'}]};
        basket.render(mock.products);
    };

    basket.renderBasket = function (products) {
        var table       = "<% _.each(products, function(product) { %> " + miApp.getTemplate('basket', 'table') +  " <% }); %>";
        table           = _.template(table, {products : products});

        var viewCompiled    = _.template(miApp.getTemplate('basket', 'product-list'));
        viewCompiled        = viewCompiled({tableBody : table});

        return viewCompiled;
    };

    basket.renderDesc = function(params) {
        var paragraph = _.template(miApp.getTemplate('basket', 'product-desc'));
        paragraph = paragraph({params : params});

        return paragraph;
    }

    basket.count = function(products) {
        var sum = 0;
        _.each(products, function(product){ sum += parseInt(product.price); });

        return { count : products.length, price : sum };
    }

    basket.render = function(products) {
        basket.html.basket = basket.renderBasket(products);
        basket.html.desc   = basket.renderDesc(basket.count(products));

        $('[data-widget=basket]').append(basket.html.basket);
        $('[data-widget=basket]').prepend(basket.html.desc);
    };

    basket.init = function (el) {
        basket.update();
        miApp.cacheSelector('basket', $('#basket'));

        $('.basket-show').click(function(e) {
            basket.update();
            miApp.cacheSelector('basket').fadeToggle();
            return false;
        });
    }

    return {
        name : 'basket',
        init : basket.init,
        templates : ['table', 'product-list', 'product-desc']
    };
});