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
        <section class="modal-card-body">
            <form class='slot-form' action='<?php if (isCurrentUserCustomer()): ?>../ReservationController/reservationStep1<?php else: ?>../AdminActivityController/modifyPlanning<?php endif; ?>' method='post'>
                <?php if(isCurrentUserCustomer()): ?>
                    <div class="time-slots"></div>
                    <input type="hidden" name="event_modal_time" class="event_modal_time">
                    <input type="hidden" name="event_modal_date" class="event_modal_date">
                    <input type="hidden" name="event_modal_price" class="event_modal_price">
                    <input type="hidden" name="event_modal_promotion_ids" class="event_modal_promotion_ids">
                <?php endif; ?>
                <input type="hidden" name="event_modal_pla_id" class="event_modal_pla_id">
                <input type="hidden" name="event_modal_tsl_id" class="event_modal_tsl_id">
            </form>
        </section>

        <footer class="modal-card-foot">
            <div class="buttons">
                <button class="button is-primary action-event-modal"></button>
                <button class="button close-event-modal">Annuler</button>
            </div>
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
    $events[$index]['tslId']    = $date['tsl_id'];
    $events[$index]['slots']    = $date['slots'];

    $sessionNumber++;
}
?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var eventModal          = $('.event-modal');
        var eventModalTitle     = $('.modal-card-title');
        var evenModalPlaId      = $('.event_modal_pla_id');
        var evenModalTslId      = $('.event_modal_tsl_id');

        $('.action-event-modal').click(function () {
            $('.slot-form').submit();

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
                evenModalTslId[0].value = calEvent.event._def.extendedProps.tslId;
                <?php if(isCurrentUserCustomer()): ?>
                    $('.action-event-modal').text("Continuer");
                    let slots = calEvent.event._def.extendedProps.slots;

                    let slotsDiv = $('.time-slots');
                    slotsDiv.empty();


                    let selectedDate = new Date(calEvent.event.start);

                    let day     = ("0" + selectedDate.getDate()).slice(-2);
                    let month   = ("0" + (selectedDate.getMonth() + 1)).slice(-2);
                    let date    = day + '-' + month + '-' + selectedDate.getFullYear();

                    $('.event_modal_date').attr('value', date);

                    let priceDiv;
                    let promotionDiv;

                    for (let i = 0; i < slots.length; i++ ){
                        let value            = slots[i]['start'].slice(0, -3) + ' ' + slots[i]['end'].slice(0, -3);
                        let availableTickets = slots[i]['participantNb'];

                        let basePrice        = slots[i]['base_price'];
                        let price            = slots[i]['price'];
                        let promotions       = slots[i]['promotions'];

                        let isPromotions = basePrice !== price;

                        let className = '';
                        if (availableTickets === 0){
                            className = 'disabled';
                        }

                        let promotionIds = '';

                        if (isPromotions){
                            priceDiv = '<div class="prices">' +
                                '<div class="base-price">' + basePrice + ' €</div>' +
                                '<div class="price special-price">' + price + ' €</div>' +
                                '</div>';
                            promotionDiv = '<div class="promotions">';

                            let promotionTitleArray     = Object.values(promotions);
                            let promotionsIdsArray      =  Object.keys(promotions);

                            promotionIds            = promotionsIdsArray.join(',');

                            promotionTitleArray.forEach(function(element) {
                                promotionDiv += '<div class="promotion">' + element + '</div>'
                            });

                            promotionDiv += '</div>';
                        }else{
                            priceDiv = '<div class="prices">' +
                                '<div class="price">' + price + ' €</div>' +
                                '</div>';
                            promotionDiv = '<div class="promotions"></div>';
                        }

                        <?php //@TODO create good infobox?>

                        slotsDiv.append('<label class="checkbox ' + className + '">' +
                            '<div class="row">' +
                            '<span class="time">' + value + '</span>' +
                            '<input type="checkbox" name="' + value + '">' +
                            priceDiv +
                            '</div>' +
                            '<div class="row">' +
                            '<div class="availableTickets">' + availableTickets + ' places disponibles</div>' +
                            '</div>' +
                            promotionDiv +
                            '<input type="hidden" class="promotionIds" value="' + promotionIds + '">' +
                            '</label>');
                    }

                    $('.action-event-modal').attr("disabled", true);

                    $('label.checkbox:not(.disabled)').change(function () {
                        $('label.checkbox.selected').each(function () {
                            $(this).removeClass('selected');
                        });

                        this.classList.toggle('selected');

                        $('.action-event-modal').attr("disabled", false);

                        let selectedCheckboxSpan        = $('.checkbox.selected .time');
                        let priceDiv                    = $('.checkbox.selected .price');
                        let selectedPromotionIdsInput   = $('.checkbox.selected .promotionIds');

                        $('.event_modal_time').attr('value', selectedCheckboxSpan[0].innerHTML.replace(' ','-'));
                        $('.event_modal_price').attr('value', priceDiv[0].innerHTML.replace(' €',''));
                        $('.event_modal_promotion_ids').attr('value', selectedPromotionIdsInput.attr('value'));
                    });
                <?php else: ?>
                    $('.action-event-modal').text("Modifier");
                <?php endif; ?>
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