<div class="page-title">
    Réservation
</div>

<div class="session-title">
    <?php echo $activity['act_name']; ?>
    <?php echo $selectedDate; ?>
    <?php echo $selectedTime; ?>
</div>


<div class="field">
    <label for="participant_nb">Nombre de participant</label>
    <div class="control">
        <div class="select">
            <select class="select select-participant-nb" name="participant_nb">
                <?php for ($i = 1; $i < $availableTickets + 1; $i++): ?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
        </div>
    </div>
</div>

<div class="field">
    <div class="control">
        <button class="button is-link validate-participant-nb">Valider</button>
    </div>
</div>

<form class="reservationForm" method="post" action="create">
    <div class="participants"></div>
    <input type="hidden" name="tsl_id" value="<?php echo $tslId; ?>">
    <div class="field">
        <div class="control">
            <button class="button is-link">Valider</button>
        </div>
    </div>
</form>

<script>
    $('.validate-participant-nb').click(function () {
        let participantNb   = $( ".select-participant-nb option:selected" ).text();
        let participants = $('.participants');

        let currentParticipantsCount = $('.participant').length;

        if (currentParticipantsCount === participantNb){
            return true;
        }

        if (currentParticipantsCount > participantNb){
            for (let i = currentParticipantsCount; i > participantNb; i--){
                $('.participant-'+i).remove();
            } 
        }

        if (participantNb > currentParticipantsCount){
            for (let i = currentParticipantsCount; i < participantNb; i++){
                let participantIndex =  parseInt(i) + 1;

                participants.append('<div class="participant participant-' + participantIndex + '">\n' +
                    '            <h1>Participant n°' + participantIndex + '</h1>\n' +
                    '            <div class="columns">\n' +
                    '                <div class="column">\n' +
                    '                    <div class="field">\n' +
                    '                        <label for="usr_firstname">Prénom</label>\n' +
                    '                        <div class="control">\n' +
                    '                            <input type="text" class="input" name="participant-[' + participantIndex + '][usr_firstname]" required>\n' +
                    '                        </div>\n' +
                    '                    </div>\n' +
                    '                </div>\n' +
                    '                <div class="column">\n' +
                    '                    <div class="field">\n' +
                    '                        <label for="usr_lastname">Nom</label>\n' +
                    '                        <div class="control">\n' +
                    '                            <input type="text" class="input" name="participant-[' + participantIndex + '][usr_lastname]" required>\n' +
                    '                        </div>\n' +
                    '                    </div>\n' +
                    '                </div>\n' +
                    '            </div>\n' +
                    '        </div>')
            }
        }
    })
</script>