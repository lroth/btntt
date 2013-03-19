//global define
//global Handlebars

define(['handlebars', 'moment'], function (handlebars, moment) {
    "use strict";

    Handlebars.registerHelper('leadDateFormat', function (date) {
        return moment(date).format('MMMM DD YYYY');
    });

    Handlebars.registerHelper('formInputWidget', function () {
        var inputStr = '';

        //@TODO: make sub views from this
        switch (this.type) {
            case 'textarea' :
                inputStr =
                    '<textarea name="' + this.name +
                        '">Add your ' + this.name + '...</textarea>';
                break;

            case 'choice':
                inputStr = '<select name="' + this.name + '">';
                for (var i = 0; i < this.choices.length; i++) {
                    inputStr += '<option value="' + this.choices[i].value + '">' + this.choices[i].label + '</option>';
                }
                inputStr += '</select>';
                break;

            default :
                inputStr =
                    '<input data-format="' + this.format +
                        '" type="' + this.type +
                        '" placeholder="' + this.name +
                        '" name="' + this.name + '"/>'
                ;
                break;
        }

        return new Handlebars.SafeString(inputStr);
    });
});