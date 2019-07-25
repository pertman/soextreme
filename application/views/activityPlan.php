<div class="page-title">
    Plannification d'activité
</div>

<div class="activity-plan-container">
    <div class="calendarPlan">
        <form method="post" action="planActivity">
        <div class="field">
            <label for="date_range">Période</label>
            <input type="datetime" id="dateTimePicker" name="date_range" required>
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
    const calendars = bulmaCalendar.attach('#dateTimePicker' ,{
        dateFormat: 'YYYY-MM-DD',
        displayMode: 'inline',
        isRange: true,
        weekStart: 1,
        minuteSteps: '1',
        showFooter: 'false',
        color: '#4462a5',
    });

    const element = document.querySelector('#dateTimePicker');
    if (element) {
        element.bulmaCalendar.on('select', datepicker => {
            document.getElementById("validate-button").disabled = false;
        });
        element.bulmaCalendar.on('select:start', datepicker => {
            document.getElementById("validate-button").disabled = true;
        });
    }

    $('label.checkbox').change(function () {
        this.classList.toggle('selected');
    })
</script>