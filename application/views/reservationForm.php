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


    <div class="buttons">
        <button class="button is-link validate-participant-nb">Valider</button>
    </div>


<form class="reservationForm" method="post" action="create">
    <div class="participants">
    </div>
    <input type="hidden" name="tsl_id" value="<?php echo $tslId; ?>">
    <div class="buttons">
        <div class="field">
            <div class="control">
                <button class="button validate-reservation-form hidden is-link">Valider</button>
            </div>
        </div>
    </div>
</form>

<script>
    $('.validate-participant-nb').click(function () {
        let participantNb   = $( ".select-participant-nb option:selected" ).text();
        let participants = $('.participants');

        let currentParticipantsCount = $('.participant').length;

        $('.validate-reservation-form').removeClass('hidden');

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

                participants.append('<div class="participant participant-' + participantIndex + '">\n'+
'            <div class="participant-label">Participant n°' + participantIndex + '</div>\n'+
'            <div class="columns">\n'+
'                <div class="column">\n'+
'                    <div class="field">\n'+
'                        <label for="usr_firstname">Prénom</label>\n'+
'                        <div class="control">\n'+
'                            <input type="text" class="input" name="participant-[' + participantIndex + '][usr_firstname]" required>\n'+
'                        </div>\n'+
'                    </div>\n'+
'                </div>\n'+
'                <div class="column">\n'+
'                    <div class="field">\n'+
'                        <label for="usr_lastname">Nom</label>\n'+
'                        <div class="control">\n'+
'                            <input type="text" class="input" name="participant-[' + participantIndex + '][usr_lastname]" required>\n'+
'                        </div>\n'+
'                     </div>\n'+
'                 </div>\n'+
'            </div>\n'+
'            <div class="columns">\n'+
'                <div class="column">\n'+
'                    <div class="field">\n'+
'                        <label for="usr_age">Age</label>\n'+
'                        <div class="control">\n'+
'                            <input type="number" class="input" min="<?php echo $activity['act_required_age']; ?>" name="participant-[' + participantIndex + '][usr_age]" required>\n'+
'                        </div>\n'+
'                    </div>\n'+
'                </div>\n'+
'                <div class="column">\n'+
'                    <div class="field">\n'+
'                       <label for="usr_gift_email">Email du destinataire du cadeau (Optionnel) </label>\n'+
'                         <div class="control">\n'+
'                           <input type="text" class="input" name="participant-[' + participantIndex + '][usr_gift_email]">\n'+
'                        </div>\n'+
'                    </div>\n'+
'                </div>\n'+
'            </div>\n'+
'        </div>')
            }
        }
    })
</script>