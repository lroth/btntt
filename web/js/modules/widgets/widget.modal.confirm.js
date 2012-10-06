define('widgets/widget.modal.confirm', ['abstract/Modal', 'widgets/widget'], function (Modal, widgetFactory) {
    function ModalConfirm() {
        this.actionUrl  = null,
        this.properties = {
            title   : 'Are you sure?',
            buttons : {
                cancel : { label : 'Anuluj' },
                submit : { label : 'OK' }
            }
        }
    };

    ModalConfirm = miApp.extend(Modal, ModalConfirm);

    ModalConfirm.prototype.init = function(el) {
        this.parent.init.call(this, el);
        this.setUrl();
        this.bindButtons();

        this.renderModal('<p>Potwierdż akcję</p>');
    };

    ModalConfirm.prototype.setUrl = function() {
        this.actionUrl = ($(this.target).attr('data-action') || $(this.target).attr('href'));
    };

    ModalConfirm.prototype.makeAction = function() {
        // miApp.request({url: this.actionUrl, type: 'GET'}, function() { location.reload() });
        window.location.href = this.actionUrl;
    };

    ModalConfirm.prototype.bindButtons = function() {
        $('body').delegate(this.selector, 'modalSubmit', _.bind(this.makeAction, this));
    };

    widgetFactory.setFactoryClass(ModalConfirm);
    return widgetFactory;
});