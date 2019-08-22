<div class="page-title">
    Plannification d'activité
</div>

<div class="activity-plan-container">
    <div class="calendarPlan">
        <form method="post" action="planActivity">
        <div class="field">
            <label for="date_range">Période</label>
            <input type="date" id="datePicker" name="date_range" required>
            <input type="time" id="timePicker" required>
            <input type="hidden" id="timeStart" name="timeStart" value="">
            <input type="hidden" id="timeEnd" name="timeEnd" value="">
        </div>
            <label>Jours :</label>
            <div class="field daySelectPlan" >
            <label class="checkbox">
                <span class="value">
                     Lundi
                </span>
                <input type="checkbox" name="monday">
            </label>
            <label class="checkbox">
                <span class="value">
                    Mardi
                </span>
                <input type="checkbox" name="tuesday">
            </label>
            <label class="checkbox">
                <span class="value">
                    Mercredi
                </span>
                <input type="checkbox" name="wednesday">
            </label>
            <label class="checkbox">
                <span class="value">
                    Jeudi
                </span>
                <input type="checkbox" name="thursday">
            </label>
            <label class="checkbox">
                <span class="value">
                    Vendredi
                </span>
                <input type="checkbox" name="friday">
            </label>
            <label class="checkbox">
                <span class="value">
                    Samedi
                </span>
                <input type="checkbox" name="saturday">
            </label>
            <label class="checkbox">
                <span class="value">
                    Dimanche
                </span>
                <input type="checkbox" name="sunday">
            </label>
        </div>
            <div class="field">
                <input type="hidden" name="act_id" value="<?php echo $activity["act_id"]; ?>">
                <div class="buttons">
                    <div class="control">
                        <button id="validate-button" class="button is-link" disabled>Valider</button>
                    </div>
                </div>
            </div>
    </div>
</div>
<script>
    const datePicker = bulmaCalendar.attach('#datePicker' ,{
        dateFormat: 'YYYY-MM-DD',
        displayMode: 'inline',
        isRange: true,
        weekStart: 1,
        minuteSteps: '1',
        showFooter: 'false',
        color: '#4462a5',
    });

    const timePicker = bulmaCalendar.attach('#timePicker' ,{
        dateFormat: 'YYYY-MM-DD',
        displayMode: 'inline',
        isRange: true,
        weekStart: 1,
        minuteSteps: '1',
        showFooter: 'false',
        color: '#4462a5',
    });

    const datePickerElement = document.querySelector('#datePicker');
    if (datePickerElement) {
        datePickerElement.bulmaCalendar.on('select', datepicker => {
            document.getElementById("validate-button").disabled = false;
        });
        datePickerElement.bulmaCalendar.on('select:start', datepicker => {
            document.getElementById("validate-button").disabled = true;
        });
    }

    $('label.checkbox').change(function () {
        this.classList.toggle('selected');
    });

    const timePickerElement = document.querySelector('#timePicker');

    timePickerElement.bulmaCalendar.on('select', timePicker => {

    });

    //Start Hours
    $('.timepicker-start .timepicker-hours .timepicker-next').on('click', function () {
        var startHoursElement   = $('.timepicker-start .timepicker-hours .timepicker-input-number');
        var startMinutesElement = $('.timepicker-start .timepicker-minutes .timepicker-input-number');

        let startHours      = startHoursElement[0].innerHTML;
        let startMinutes    = startMinutesElement[0].innerHTML;

        let newStartTime;
        if (startHours === '23'){
            newStartTime = "00:" + startMinutes
        }else{
            newStartTime = formatHours(parseInt(startHours) + 1) + ":" + startMinutes;
        }

        $('#timeStart')[0].value = newStartTime;
    });

    $('.timepicker-start .timepicker-hours .timepicker-previous').on('click', function () {
        var startHoursElement   = $('.timepicker-start .timepicker-hours .timepicker-input-number');
        var startMinutesElement = $('.timepicker-start .timepicker-minutes .timepicker-input-number');

        let startHours      = startHoursElement[0].innerHTML;
        let startMinutes    = startMinutesElement[0].innerHTML;

        let newStartTime;
        if (startHours === '00'){
            newStartTime = "23:" + startMinutes
        }else{
            newStartTime = formatHours(parseInt(startHours) - 1) + ":" + startMinutes;
        }

        $('#timeStart')[0].value = newStartTime;
    });

    //Start Minutes
    $('.timepicker-start .timepicker-minutes .timepicker-next').on('click', function () {
        var startHoursElement   = $('.timepicker-start .timepicker-hours .timepicker-input-number');
        var startMinutesElement = $('.timepicker-start .timepicker-minutes .timepicker-input-number');

        let startHours      = startHoursElement[0].innerHTML;
        let startMinutes    = startMinutesElement[0].innerHTML;

        let newEndTime;
        if (startMinutes === '59'){
            if (startHours === '23'){
                newEndTime = "00:00";
            }else{
                newEndTime =  formatHours(parseInt(startHours) + 1) + ":00";
            }
        }else{
            newEndTime = startHours + ":" + formatHours(parseInt(startMinutes) + 1);
        }

        $('#timeStart')[0].value = newEndTime;
    });

    $('.timepicker-start .timepicker-minutes .timepicker-previous').on('click', function () {
        var startHoursElement   = $('.timepicker-start .timepicker-hours .timepicker-input-number');
        var startMinutesElement = $('.timepicker-start .timepicker-minutes .timepicker-input-number');

        let startHours      = startHoursElement[0].innerHTML;
        let startMinutes    = startMinutesElement[0].innerHTML;

        let newEndTime;
        if (startMinutes === '00'){
            if (startHours === '00') {
                newEndTime = "23:59";
            }else{
                newEndTime = formatHours(parseInt(startHours) - 1) + ":59";
            }
        }else{
            newEndTime = startHours + ":" + formatHours(parseInt(startMinutes) - 1);
        }

        $('timeStart')[0].value = newEndTime;
    });

    //End Hours
    $('.timepicker-end .timepicker-hours .timepicker-next').on('click', function () {
        var startHoursElement   = $('.timepicker-end .timepicker-hours .timepicker-input-number');
        var startMinutesElement = $('.timepicker-end .timepicker-minutes .timepicker-input-number');

        let startHours      = startHoursElement[0].innerHTML;
        let startMinutes    = startMinutesElement[0].innerHTML;

        let newEndTime;
        if (startHours === '23'){
            newEndTime = "00:" + startMinutes
        }else{
            newEndTime = formatHours(parseInt(startHours) + 1) + ":" + startMinutes;
        }

        $('#timeEnd')[0].value = newEndTime;
    });

    $('.timepicker-end .timepicker-hours .timepicker-previous').on('click', function () {
        var startHoursElement   = $('.timepicker-end .timepicker-hours .timepicker-input-number');
        var startMinutesElement = $('.timepicker-end .timepicker-minutes .timepicker-input-number');

        let startHours      = startHoursElement[0].innerHTML;
        let startMinutes    = startMinutesElement[0].innerHTML;

        let newEndTime;
        if (startHours === '00'){
            newEndTime = "23:" + startMinutes
        }else{
            newEndTime = formatHours(parseInt(startHours) - 1) + ":" + startMinutes;
        }

        $('#timeEnd')[0].value = newEndTime;
    });

    //End Minutes
    $('.timepicker-end .timepicker-minutes .timepicker-next').on('click', function () {
        var startHoursElement   = $('.timepicker-end .timepicker-hours .timepicker-input-number');
        var startMinutesElement = $('.timepicker-end .timepicker-minutes .timepicker-input-number');

        let startHours      = startHoursElement[0].innerHTML;
        let startMinutes    = startMinutesElement[0].innerHTML;

        let newStartTime;
        if (startMinutes === '59'){
            if (startHours === '23'){
                newStartTime = "00:00";
            }else{
                newStartTime =  formatHours(parseInt(startHours) + 1) + ":00";
            }
        }else{
            newStartTime = startHours + ":" + formatHours(parseInt(startMinutes) + 1);
        }

        $('#timeEnd')[0].value = newStartTime;
    });

    $('.timepicker-end .timepicker-minutes .timepicker-previous').on('click', function () {
        var startHoursElement   = $('.timepicker-end .timepicker-hours .timepicker-input-number');
        var startMinutesElement = $('.timepicker-end .timepicker-minutes .timepicker-input-number');

        let startHours      = startHoursElement[0].innerHTML;
        let startMinutes    = startMinutesElement[0].innerHTML;

        let newStartTime;
        if (startMinutes === '00'){
            if (startHours === '00') {
                newStartTime = "23:59";
            }else{
                newStartTime = formatHours(parseInt(startHours) - 1) + ":59";
            }
        }else{
            newStartTime = startHours + ":" + formatHours(parseInt(startMinutes) - 1);
        }

        $('#timeEnd')[0].value = newStartTime;
    });

    function formatHours(n){
        return n > 9 ? "" + n: "0" + n;
    }

</script>