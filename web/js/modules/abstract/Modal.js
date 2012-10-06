define('abstract/Modal', ['bootstrap-modal'], function () {
    function Modal(){
        this.id         = null;
        this.cssId      = 'bar';
        this.target     = null;

        this.properties = {
            title   : null,
                body    : null,
                buttons : {
                cancel : { label : 'zrezygnuj' },
                submit : { label : 'zatwierdź' }
            }
        };
    }

    Modal.prototype = {
        init : function(el) {
            this.target = el;

            this.setModalId();
            this.setModalSelector();

            this.bindBehaviours();
        },

        setModalId : function() {
            this.id = _.uniqueId();
            miApp.setModal(this.id, this);
        },

        getLayout : function(callback) {
            miApp.loadTemplates('modal', ['main'], callback);
        },

        renderLayout : function() {
            var template    = _.template(miApp.templates['modal']['main']);
            var properties  = {};

            properties.title   = this.properties.title;
            properties.buttons = this.properties.buttons,
            properties.body    = this.properties.body,
            properties.modalId = this.id

            template = template(properties);

            //TODO: check if append is correct
            $('body').append(template);
        },

        renderModal : function(body) {
            this.properties.body = body;
            this.getLayout(_.bind(this.renderLayout, this) );
        },

        setModalSelector : function() {
            this.selector  = '#modal-' + this.id + '.modal ';
        },

        openModal : function() {
            $(this.selector).modal('toggle');
            return false;
        },

        fireButtonEvent : function() {
            $(this).trigger('modal' + _.captFirst($(this).attr('data-type')));
        },

        bindBehaviours : function() {
            $(this.target).click(_.bind(this.openModal, this));

            $('body').delegate(this.selector + ' button', 'click', this.fireButtonEvent);
        }
    }

    return (Modal);
});