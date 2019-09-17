<?php $dateRange    = ''; ?>
<?php $startTime    = ''; ?>
<?php $endTime      = ''; ?>
<?php $isMonday     = false; ?>
<?php $isTuesday    = false; ?>
<?php $isWednesday  = false; ?>
<?php $isThursday   = false; ?>
<?php $isFriday     = false; ?>
<?php $isSaturday   = false; ?>
<?php $isSunday     = false; ?>

<?php if (isset($planning)): ?>
    <?php if (isset($planning['pla_id'])): ?>

    <?php else: ?>
        <?php $dateRange    = $planning['date_range']; ?>
        <?php $startTime    = $planning['tsl_hour_start']; ?>
        <?php $endTime      = $planning['tsl_hour_end']; ?>
        <?php $isMonday     = isset($planning['monday']); ?>
        <?php $isTuesday    = isset($planning['tuesday']); ?>
        <?php $isWednesday  = isset($planning['wednesday']); ?>
        <?php $isThursday   = isset($planning['thursday']); ?>
        <?php $isFriday     = isset($planning['friday']); ?>
        <?php $isSaturday   = isset($planning['saturday']); ?>
        <?php $isSunday     = isset($planning['sunday']); ?>
    <?php endif; ?>
<?php endif; ?>

<?php $startDate = ''; ?>
<?php $endDate   = ''; ?>

<?php if ($dateRange): ?>
    <?php if (strpos($dateRange, ' - ') !== false): ?>
        <?php $dateRangeArray = explode(' - ', $dateRange); ?>
        <?php $startDate = $dateRangeArray[0]; ?>
        <?php $endDate   = $dateRangeArray[1]; ?>
    <?php else: ?>
        <?php $startDate = $dateRange; ?>
    <?php endif; ?>
<?php endif; ?>

<div class="page-title">
    Plannification d'activité
</div>

<div class="activity-plan-container">
    <div class="calendarPlan">
        <form method="post" action="planActivity">
            <div class="field">
                <label for="date_range">Période</label>
                <input type="date" id="datePicker" name="date_range" required value="<?php echo $dateRange; ?>">
            </div>
            <div class="field">
                <label for="tsl_hour_start">Heure de début</label>
                <input type="text" class="input tsl_hour_start" id="timeStart" name="tsl_hour_start" value="<?php echo $startTime;?>">
            </div>

            <div class="field">
                <label for="tsl_hour_end">Heure de Fin</label>
                <input type="text" class="input tsl_hour_end" id="timeEnd" name="tsl_hour_end" value="<?php echo $endTime?>">
            </div>

            <label>Jours :</label>
            <div class="field daySelectPlan">
            <label class="checkbox <?php if ($isMonday): ?>selected<?php endif; ?>">
                <span class="value">
                     Lundi
                </span>
                <input type="checkbox" name="monday" <?php if ($isMonday): ?>checked<?php endif; ?>>
            </label>
            <label class="checkbox <?php if ($isTuesday): ?>selected<?php endif; ?>">
                <span class="value">
                    Mardi
                </span>
                <input type="checkbox" name="tuesday" <?php if ($isTuesday): ?>checked<?php endif; ?>>
            </label>
            <label class="checkbox <?php if ($isWednesday): ?>selected<?php endif; ?>">
                <span class="value">
                    Mercredi
                </span>
                <input type="checkbox" name="wednesday" <?php if ($isWednesday): ?>checked<?php endif; ?>>
            </label>
            <label class="checkbox <?php if ($isThursday): ?>selected<?php endif; ?>">
                <span class="value">
                    Jeudi
                </span>
                <input type="checkbox" name="thursday" <?php if ($isThursday): ?>checked<?php endif; ?>>
            </label>
            <label class="checkbox <?php if ($isFriday): ?>selected<?php endif; ?>">
                <span class="value">
                    Vendredi
                </span>
                <input type="checkbox" name="friday" <?php if ($isFriday): ?>checked<?php endif; ?>>
            </label>
            <label class="checkbox <?php if ($isSaturday): ?>selected<?php endif; ?>">
                <span class="value">
                    Samedi
                </span>
                <input type="checkbox" name="saturday" <?php if ($isSaturday): ?>checked<?php endif; ?>>
            </label>
            <label class="checkbox <?php if ($isSunday): ?>selected<?php endif; ?>">
                <span class="value">
                    Dimanche
                </span>
                <input type="checkbox" name="sunday" <?php if ($isSunday): ?>checked<?php endif; ?>>
            </label>
        </div>
        <div class="field">
            <input type="hidden" name="act_id" value="<?php echo $actId; ?>">
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
        startDate: '<?php echo $startDate; ?>',
        endDate: '<?php echo $endDate; ?>',
        showFooter: 'false',
        color: '#4462a5',
    });

    <?php if ($dateRange): ?>
        $('#datePicker')[0].value = '<?php echo $dateRange; ?>';
        document.getElementById("validate-button").disabled = false;
    <?php endif; ?>

    $('#timeStart').timepicker({
        timeFormat: 'HH:mm',
        interval: 10,
        minTime: '6:00',
        maxTime: '22:00',
        defaultTime: '<?php echo $startTime; ?>',
        startTime: '6:00',
        dynamic: false,
        dropdown: true,
        scrollbar: true
    });

    $('#timeEnd').timepicker({
        timeFormat: 'HH:mm',
        interval: 10,
        minTime: '6:00',
        maxTime: '22:00',
        defaultTime: '<?php echo $endTime; ?>',
        startTime: '6:00',
        dynamic: false,
        dropdown: true,
        scrollbar: true
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

    $('.reset-period').click(function () {
        $('#datePicker')[0].value = '';
        $('#datePicker')[0].bulmaCalendar.datePicker.clear();
    });

</script>