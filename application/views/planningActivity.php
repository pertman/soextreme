<?php //@TODO calendar activity ?>
<?php echo "Planning " . $activity['act_name']; ?>
<div id="calendar" class="calendar-activity-container">

</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: [ 'dayGrid', 'timeGrid', 'list' ], // an array of strings!
            locale: 'fr',
            defaultView: 'timeGridWeek',
            eventSources: [

                // your event source
                {
                    events: [ // put the array in the `events` property
                        {
                            title  : 'event1',
                            start  : '2019-07-01T00:00:00-05:00',
                            end: '2019-07-12T00:00:00-05:00'
                        },
                        {
                            title  : 'event2',
                            start  : '2019-07-26',
                            end    : '2019-07-28'
                        },
                        {
                            title  : 'event3',
                            start  : '2019-07-29T12:30:00',
                        }
                    ],
                    color: 'white',     // an option!
                    textColor: 'blue' // an option!
                }

                // any other event sources...

            ]
        });

        calendar.render();
    });

</script>