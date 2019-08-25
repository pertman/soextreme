<div class="page-title">
    <?php echo "Planning " . $activity['act_name']; ?>
</div>

<?php //@TODO remove if month view ?>
<div class="fc-button-group zoom-buttons">
    <button type="button" class="fc-button fc-button-primary zoom-in-button" aria-label="zoom-in">
        <i class="fas fa-plus"></i>
    </button>
    <button type="button" class="fc-button fc-button-primary zoom-out-button" aria-label="zoom-out">
        <i class="fas fa-minus"></i>
    </button>
</div>

<div id="calendar" class="calendar-activity-container"></div>

<div class="modal event-modal">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title"></p>
            <button class="delete close-event-modal" aria-label="close"></button>
        </header>
        <input type="hidden" class="event_modal_pla_id">
        <footer class="modal-card-foot">
            <button class="button is-primary modify-event-modal">Modifier</button>
            <button class="button close-event-modal">Annuler</button>
        </footer>
    </div>
</div>
<?php

$events = array();
$sessionNumber = 1;

foreach ($dates as $index => $date){
    $events[$index]['title']    = $activity['act_name'] . " Session " . $sessionNumber;
    $events[$index]['date']     = $date['date'];
    $events[$index]['start']    = $date['date'] . "T" . $date['start'];
    $events[$index]['end']      = $date['date'] . "T" . $date['end'];
    $events[$index]['plaId']    = $date['pla_id'];
    $sessionNumber++;
}
?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var baseUrl             = '<?php echo base_url() ?>';
        var eventModal          = $('.event-modal');
        var eventModalTitle     = $('.modal-card-title');
        var evenModalPlaId      = $('.event_modal_pla_id');

        $('.modify-event-modal').click(function () {
            window.location.replace(baseUrl + "AdminActivityController/modifyPlanning?id=" + evenModalPlaId[0].value);
        });
        $('.close-event-modal').click(function () {
            eventModal[0].classList.remove('is-active')
        });

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
            navLinks: true,
            // eventLimit: 1,
            defaultView: $(window).width() < 760 ? 'timeGridDay' : 'timeGridWeek',
            header: { right: $(window).width() < 760 ? 'timeGridDay,today prev,next': 'dayGridMonth,timeGridWeek,timeGridDay,today prev,next' },
            displayEventEnd: true,
            minTime: "08:00:00",
            maxTime: "18:00:00",
            slotDuration: "00:05:00",
            aspectRatio: 1,
            height: "auto",
            eventSources: [
                {
                    events: events,
                    color: '#e38b3f',
                    textColor: 'white'
                }
            ],
            eventClick: function(calEvent, jsEvent, view, resourceObj) {
                eventModal[0].classList.add('is-active');
                eventModalTitle[0].innerHTML = calEvent.event.title;
                evenModalPlaId[0].value = calEvent.event._def.extendedProps.plaId;
            }
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