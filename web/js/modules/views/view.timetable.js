define('views/view.timetable', ['datepicker', 'bootstrap-typeahead'], function () {
    var timeTable = {};

    activateDateSelector = function() {
        //copy current state value
        $('.timeContainer').val($('button.select_date.active').attr('data-value'));

        //regular buttons
        $('button.select_date').click(function(e) {
            $('button.select_date, button.select_calendar').removeClass('active');
            e.preventDefault();
            $(this).addClass('active');
            $('.timeContainer').val($(this).attr('data-value'));
            $('#btn_appbundle_timetype_created_at').datepicker('hide');
        })

        //calendar selector
        $('#btn_appbundle_timetype_created_at').datepicker({
            calendars: 1,
            starts: 1
        }).on('changeDate', function(ev){
            var d1 = new Date(ev.date);
            var new_date = d1.getDate() + '/' + d1.getMonth() + '/' + d1.getFullYear();
            $('button.select_date').removeClass('active');
            $('button.select_calendar').addClass('active').html('<i class="icon-calendar"></i> ' + new_date);
            $('#btn_appbundle_timetype_created_at').datepicker('hide');
        });

        //attach calendar open
        $('button.select_calendar').click(function(e) {
            e.preventDefault();
            $('#btn_appbundle_timetype_created_at').datepicker('show');
        })


        // $('#btn_appbundle_timetype_created_at').DatePicker();

    }

    activateTimeEdit = function() {

        //edit current row

        // $('.edit_time').click(function(e) {
        $('div.view').delegate('.edit_time', 'click', function(e) {
            e.preventDefault();

            //load edit form from backend
            var el = $(this);
            el.toggleClass('loading disabled');
            $.ajax({
                url: el.attr('data-href'),
                success: function(data) {
                    //replace row with form
                    el.parent().parent().replaceWith(data);

                    //lock other edit buttons to show only one form at once
                    $('div.view').find('.edit_time').toggleClass('disabled');

                    //attach events to form items
                    $("#editTime").find('#btn_appbundle_timetype_created_at').datepicker({
                        calendars: 1,
                        starts: 1
                    });

                    //attach autocomplete for projects
                    $("#editTime").find('#btn_appbundle_timetype_project').typeahead({
                        source: function(query, process) {
                            miApp.request({type: 'GET', url: this.$element.attr('data-source')}, _.bind(process));
                        }
                    });

                    /* attach a submit handler to the form */
                      $("#editTime").submit(function(event) {
                        event.preventDefault();

                        /* get some values from elements on the page: */
                        var form = $(this),
                            url  = form.attr( 'action' );

                        form.find('.btn').toggleClass('loading disabled');

                        /* Send the data using post and put the results in a div */
                        $.post( url, form.serialize(),
                            function(data) {
                                form.parent().parent().replaceWith(data);

                                //unlock other edit buttons to show only one form at once
                                $('div.view').find('.edit_time').toggleClass('disabled');
                            }
                        );
                      });
                }
            })
        })
    }

    activateTimeDelete = function() {
        $('div.view').delegate('.delete_time', 'click', function(e) {
            e.preventDefault();
            //load edit form from backend
            var el = $(this);
            el.toggleClass('loading disabled');
            $.ajax({
                url: el.attr('data-href'),
                success: function(data) {
                    //replace row with form
                    el.parent().parent().remove();
                }
            });
        });
    }

    timeTable.init = function(el) {
        console.log('Here I am');
        activateDateSelector();

        activateTimeEdit();

        activateTimeDelete();
    };

    return {
        init : timeTable.init
    };
});