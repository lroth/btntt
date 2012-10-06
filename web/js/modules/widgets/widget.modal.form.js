define('widgets/widget.modal.form', ['abstract/Modal', 'widgets/widget'], function (Modal, widgetFactory) {
    function ModalForm() {
        this.formUrl  = null;
    };

    ModalForm = miApp.extend(Modal, ModalForm);

    ModalForm.prototype.init = function(el) {
        this.parent.init.call(this, el);
        this.setUrl();
        this.getForm();

        this.bindButtons();
    };

    ModalForm.prototype.setUrl = function() {
        this.formUrl = $(this.target).attr('data-action');
    };

    ModalForm.prototype.getForm = function() {
        miApp.request({type: 'GET', url: this.formUrl}, _.bind(this.renderModal, this));
    };

    ModalForm.prototype.bindButtons = function() {
        $('body').delegate(this.selector, 'modalSubmit', function() {
            $(this).find('form').submit();
        });
    };

    widgetFactory.setFactoryClass(ModalForm);
    return widgetFactory;
});