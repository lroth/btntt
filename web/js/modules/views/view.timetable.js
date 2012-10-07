define('views/view.timetable', [], function () {
    var timeTable = {};

    activateDateSelector = function() {

        //regular buttons
        $('button.select_date').click(function(e) {
            $('button.select_date').removeClass('active');
            e.preventDefault();
            $(this).addClass('active');
            $('.timeContainer').val($(this).attr('data-value'));
        })

        //calendar selector


    }

    timeTable.init = function(el) {
        console.log('Here I am');
        activateDateSelector();
    };

    return {
        init : timeTable.init
    };
});