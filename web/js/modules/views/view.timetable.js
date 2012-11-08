define('views/view.timetable', ['d3js', 'datepicker', 'bootstrap-typeahead', 'bootstrap-popover'], function () {
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
            $('#btn_appbundle_timetype_createdAt').datepicker('hide');
        })

        //calendar selector
        $('#btn_appbundle_timetype_createdAt').datepicker({
            calendars: 1,
            starts: 1
        }).on('changeDate', function(ev){
            var d1    = new Date(ev.date);
            var month = (d1.getMonth() + 1);//bravo js Date object - months from 0 to 11 ;)
            var new_date = d1.getDate() + '/' + month + '/' + d1.getFullYear();
            $('button.select_date').removeClass('active');
            $('button.select_calendar').addClass('active').html('<i class="icon-calendar"></i> ' + new_date);
            $('#btn_appbundle_timetype_createdAt').datepicker('hide');
        });

        //attach calendar open
        $('button.select_calendar').click(function(e) {
            e.preventDefault();
            $('#btn_appbundle_timetype_createdAt').datepicker('show');
        })


        // $('#btn_appbundle_timetype_createdAt').DatePicker();

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
                    $("#editTime").find('#btn_appbundle_timetype_createdAt').datepicker({
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

    activateHistoryPopover = function() {
        $('a[rel=popover]').popover();
    }

    buildCharts = function() {

        drawPie = function(data, cell) {

            var width = 110,
                height = 110,
                radius = Math.min(width, height) / 2;

            var color = d3.scale.category20();

            var pie = d3.layout.pie()
                .sort(null);

            var arc = d3.svg.arc()
                .innerRadius(radius - 30)
                .outerRadius(radius - 5);

            var svg = d3.select(cell).append("svg")
                .attr("width", width)
                .attr("height", height + 15)
              .append("g")
                .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

            var label = svg.append("text");
            var path = svg.selectAll("path")
                .data(function(d) { return pie(data.value) })
              .enter().append("path")
                .attr("fill", function(d, i) { return color(i); })
                .attr("cursort", "pointer")
                .attr("d", arc)
                .on("mouseover", function(d, i) {
                    label.remove();
                    label = svg.append("text")
                        .attr("dy", 65)
                        .style("text-anchor", "middle")
                        .style("font-size", "11px")
                        .text(data.label[i] + ' ' + data.value[i] + 'h');
                })
                .on("mouseout", function(d, i) {
                    label.remove();
                });

            var text = svg.append("text")
                .attr("dy", ".35em")
                .style("text-anchor", "middle")
                .text(function(d) { return d3.sum(data.value) + 'h'; });


        }


        //parse data
        $('td.item-data').each(function() {
            var data = { value: [], label: [] };
            $(this).find('data').each(function() {
                data.value.push(parseInt($(this).attr('data-value')));
                data.label.push($(this).attr('data-label'));
            });

            //render data
            if (data.value.length > 0) {
                console.log(data);
                console.log($(this).attr('id'));
                drawPie(data, '#' + $(this).attr('id'));
            };
        });
    }

    timeTable.init = function(el) {

        activateDateSelector();

        activateTimeEdit();

        activateTimeDelete();

        activateHistoryPopover();

        //build charts
        buildCharts();
    };

    return {
        init : timeTable.init
    };
});