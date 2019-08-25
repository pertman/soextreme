<?php echo "Planning " . $activity['act_name']; ?>
<button class="button zoom-in-button">Plus</button>
<button class="button zoom-out-button">Moins</button>
<div id="calendar" class="calendar-activity-container">
<?php //@TODO calendar activity ?>
<div class="page-title">
    <?php echo "Planning " . $activity['act_name']; ?>
</div>

</div>
<?php

$events = array();
$sessionNumber = 1;

foreach ($dates as $index => $date){
    $events[$index]['title'] = $activity['act_name'] . " Session " . $sessionNumber;
    $events[$index]['date']  = $date['date'];
    $events[$index]['start'] = $date['date'] . "T" . $date['start'];
    $events[$index]['end']   = $date['date'] . "T" . $date['end'];

    $sessionNumber++;
}
?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let jsonString = '';
        <?php foreach ($events as $event) { ?>
            jsonString += JSON.stringify(<?php echo json_encode($event); ?>);
            jsonString += ',';
        <?php }?>
        jsonString = jsonString.slice(0, -1);
        let events = $.parseJSON('[' + jsonString + ']');

        var calendarEl = document.getElementById('calendar');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: [ 'dayGrid', 'timeGrid', 'list'], // an array of strings!
            locale: 'fr',
            defaultView: 'timeGridWeek',
            displayEventEnd: true,
            minTime: "08:00:00",
            maxTime: "18:00:00",
            slotDuration: "00:05:00",
            aspectRatio: 1,
            height: "auto",
            eventSources: [

                {
                    events: events,
                    color: '#e38b3f',     // an option!
                    textColor: 'white' // an option!
                }
            ],
        });

        calendar.render();


        $('.zoom-in-button').click(function(){
            zoomIn(calendar);
        });

        $('.zoom-out-button').click(function(){
            zoomOut(calendar);
        });

        if (calendar.getOption('slotDuration') === '00:05:00'){
            $('.zoom-in-button').attr('disabled', true);
        }

        if (calendar.getOption('slotDuration') === '01:00:00'){
            $('.zoom-out-button').attr('disabled', true);
        }
    });

    function zoomIn(calendar) {
        let slotDuration = calendar.getOption('slotDuration');

        let newSlotDuration;
        switch (slotDuration) {
            case '00:15:00':
                newSlotDuration = '00:05:00';
                $('.zoom-in-button').attr('disabled', true);
                break;
            case '00:30:00':
                newSlotDuration = '00:15:00';
                break;
            case '01:00:00':
                newSlotDuration = '00:30:00';
                $('.zoom-out-button').attr('disabled', false);
                break;
        }

        calendar.setOption('slotDuration', newSlotDuration)
    }

    function zoomOut(calendar) {
        let slotDuration = calendar.getOption('slotDuration');

        let newSlotDuration;
        switch (slotDuration) {
            case '00:05:00':
                $('.zoom-in-button').attr('disabled', false);
                newSlotDuration = '00:15:00';
                break;
            case '00:15:00':
                newSlotDuration = '00:30:00';
                break;
            case '00:30:00':
                newSlotDuration = '01:00:00';
                $('.zoom-out-button').attr('disabled', true);
                break;
        }

        calendar.setOption('slotDuration', newSlotDuration)
    }
</script>