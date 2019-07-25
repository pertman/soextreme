<?php //@TODO calendar activity ?>
<?php echo "Planning " . $activity['act_name']; ?>
<div id="calendar" class="calendar-activity-container">

</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: [ 'dayGrid', 'timeGrid', 'list' ] // an array of strings!
        });

        calendar.render();
    });

</script>