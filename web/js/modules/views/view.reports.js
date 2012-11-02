define('views/view.reports', ['datepicker', 'bootstrap-popover'], function () {
    var timeReport = {};

    activateHistoryPopover = function() {
        $('a[rel=popover]').popover();
    }

    activateTimeSelector = function() {
        $('#filter_timeFrom').datepicker({
            calendars: 1,
            starts: 1
        }).on('changeDate', function(ev){
            $('#filter_timeFrom').datepicker('hide');
        });

        $('#filter_timeTo').datepicker({
            calendars: 1,
            starts: 1
        }).on('changeDate', function(ev){
            $('#filter_timeTo').datepicker('hide');
        });
    }


    timeReport.init = function(el) {
        activateHistoryPopover();
        activateTimeSelector();
    };

    return {
        init : timeReport.init
    };
});