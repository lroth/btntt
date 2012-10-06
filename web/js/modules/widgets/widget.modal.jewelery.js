define('widgets/widget.modal.jewelery', ['abstract/modal'], function (Modal) {
    var ModalJewelery = function() {},
        modalJewelery = null,

        parent = null,
        proto  = null;

    ModalJewelery   = miApp.extend(Modal, ModalJewelery);
    parent          = ModalJewelery.prototype.parent;

    ModalJewelery.prototype = {
        init : function() {

        },

        renderBody : function(body) {
            miApp.loadTemplates('modal.jewelery', ['body'], function() {
                var list = "<% _.each(images, function(image) { %> " + miApp.templates['modal.jewelery']['body'] + " <% }); %>";
                list     = _.template(list, { images: body });
            });
        },

        getBody : function() {
            miApp.request({type: 'GET', url: 'creator/get_user_jewelery'}, ModalJewelery.prototype.renderBody);
        }
    };

//        $('body').delegate('#myModal img', 'click', modal.imgClickedCallback);

    modalJewelery = new ModalJewelery();

    return {
        init : modalJewelery.init
    };
});