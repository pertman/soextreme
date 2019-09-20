<div class="page-title">
    Mon Profil
</div>

<div class="profile-info">
    <div class="columns">
        <div class="column">
            <div class="usr_firstname field">
                <div class="profile-label">Prénom</div>
                <div class="value"><?php echo $user['usr_firstname']; ?></div>
            </div>
            <div class="usr_lastname field">
                <div class="profile-label">Nom</div>
                <div class="value"><?php echo $user['usr_lastname']; ?></div>
            </div>
        </div>
        <div class="column">
            <div class="usr_email field">
                <div class="profile-label">Email</div>
                <div class="value"><?php echo $user['usr_email']; ?></div>
            </div>
            <div class="usr_phone field">
                <div class="profile-label">Téléphone</div>
                <div class="value"><?php echo $user['usr_phone']; ?></div>
            </div>
        </div>
    </div>
</div>

<div class="button is-link modifyProfileButton">Modification de vos informations</div>
<div class="button is-link modifyPasswordButton">Modification de votre mot de passe</div>
<div class="button is-link showReservation">Mes reservations</div>

<form method="post" action="updateprofile" class="modifyProfileForm hidden">
    <div class="columns">
        <div class="column">
            <div class="field">
                <label for="usr_firstname">Prénom</label>
                <div class="control">
                    <input type="text" class="input" name="usr_firstname" value="<?php echo $user['usr_firstname']; ?>">
                </div>
            </div>
            <div class="field">
                <label for="usr_lastname">Nom</label>
                <div class="control">
                    <input type="text" class="input" name="usr_lastname" value="<?php echo $user['usr_lastname']; ?>">
                </div>
            </div>
        </div>
        <div class="column">
            <div class="field">
                <label for="usr_email">Email</label>
                <div class="control">
                    <input type="mail" class="input" name="usr_email" value="<?php echo $user['usr_email']; ?>">
                </div>
            </div>
            <div class="field">
                <label for="usr_phone">Téléphone</label>
                <div class="control">
                    <input type="text" class="input" name="usr_phone" value="<?php echo $user['usr_phone']; ?>">
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="usr_id" value="<?php echo $user['usr_id']; ?>">
    <div class="field">
        <div class="control">
            <div class="buttons">
                <button class="button is-link">Modifier</button>
            </div>
        </div>
    </div>
</form>
<form method="post" action="updatePassword" class="modifyPasswordForm hidden">
    <div class="columns">
        <div class="column">
            <div class="field">
                <label for="usr_password">Mot de passe</label>
                <div class="control">
                    <input type="password" class="input" name="usr_password" required>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="field">
                <label for="usr_password_2">Confirmation Mot de passe</label>
                <div class="control">
                    <input type="password" class="input" name="usr_password_2" required>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="usr_id" value="<?php echo $user['usr_id']; ?>">
    <div class="field">
        <div class="control">
            <div class="buttons">
                <button class="button is-link">Modifier</button>
            </div>
        </div>
    </div>
</form>

<div class="reservations hidden">
    <?php foreach ($reservations as $reservation): ?>
        <div class="reservation-card card">
            <div class="card-header">
                <div class="reservation-title"><?php echo $reservation['activity']['act_name']?> le <?php echo formatDateFromUsToFr($reservation['res_date']); ?> <?php echo formatTimeSlot($reservation['res_time_slot'])?></div>
            </div>
            <div class="card-content">
                <div class="row order-date">
                    <div class="reservation-label">
                        Date de réservation :
                    </div>
                    <div class="reservation-value">
                        <?php echo formatDateAndTime($reservation['res_created_at']); ?>
                    </div>
                </div>
                <div class="row total">
                    <div class="reservation-label">
                        Prix payé :
                    </div>
                    <div class="reservation-value">
                        <?php echo formatPrice($reservation['pay_amount']).'€';?>
                    </div>
                </div>
                <div class="row participant-nb">
                    <div class="reservation-label">
                        Nombre de participants :
                    </div>
                    <div class="reservation-value">
                        <?php echo $reservation['res_participant_nb'];?>
                    </div>
                </div>
                <div class="buttons">
                    <div class="button is-link see-tickets" id="see-tickets-<?php echo $reservation['res_id']; ?>">Voir les tickets</div>
                </div>
                <div class="tickets tickets-<?php echo $reservation['res_id']; ?> hidden">
                    <div class="row">
                        <div class="total">
                            <div class="reservation-label">
                                Tickets
                            </div>
                        </div>
                    </div>
                    <?php foreach ($reservation['tickets'] as $ticket): ?>
                        <div class="ticket">
                                <div class="ticket-info">
                                    <div class="tic_firstname row">
                                        <div class="ticket-label">
                                            Prenom :
                                        </div>
                                        <div class="ticket-value">
                                            <?php echo $ticket['tic_firstname']; ?>
                                        </div>
                                    </div>
                                    <div class="tic_lastname row">
                                        <div class="ticket-label">
                                            Nom :
                                        </div>
                                        <div class="ticket-value">
                                            <?php echo $ticket['tic_lastname']; ?>
                                        </div>
                                    </div>
                                    <div class="tic_age row">
                                        <div class="ticket-label">
                                            Age :
                                        </div>
                                        <div class="ticket-value">
                                            <?php echo $ticket['tic_age']. ' ans'; ?>
                                        </div>
                                    </div>
                                    <div class="tic_price row">
                                        <div class="ticket-label">
                                            Prix :
                                        </div>
                                        <div class="ticket-value">
                                            <?php echo formatPrice($ticket['tic_price']).'€'; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="ticket-qr-code">
                                    <?php //@TODO QRCODE ?>
                                    <img src="<?php echo base_url().'uploads/tickets/'.$ticket['tic_id']; ?>" alt="qrcode">
                                </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
    $('.modifyProfileButton').click(function () {
        $('.modifyProfileForm').toggleClass('hidden');
    });

    $('.modifyPasswordButton').click(function () {
        $('.modifyPasswordForm').toggleClass('hidden');
    });

    $('.showReservation').click(function () {
        $('.reservations').toggleClass('hidden');
    });
    
    $('.see-tickets').click(function () {
       let resId = this.id.replace('see-tickets-','');
       $('.tickets-'+resId).toggleClass('hidden');
       if (this.innerHTML === 'Voir les tickets'){
           this.innerHTML = 'Masquer les tickets';
       }else{
           this.innerHTML = 'Voir les tickets';
       }
    });
</script>