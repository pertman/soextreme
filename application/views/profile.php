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

<form method="post" action="updateprofile" class="modifyProfileForm hidden" enctype="multipart/form-data">
    <div class="field">
        <label for="usr_profile_picture">Photo de profil</label>
            <?php if ($user['usr_profile_picture']): ?>
                <div class="profile-image">
                    <img src="<?php echo base_url().$user['usr_profile_picture']; ?>" alt="profile-picture">
                </div>
            <?php endif; ?>
        <div class="control">
            <input type="file" name="usr_profile_picture" id="usr_profile_picture">
        </div>
    </div>
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

<?php if ($giftReservations): ?>
    <div class="reservations">
        <div class="reservation-section-title">
            Réservations offertes
        </div>
        <?php foreach ($giftReservations as $reservation): ?>
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
                    <div class="row participant-nb">
                        <div class="reservation-label">
                            Nombre de participants :
                        </div>
                        <div class="reservation-value">
                            <?php echo $reservation['res_participant_nb'];?>
                        </div>
                    </div>
                    <div class="tickets tickets-<?php echo $reservation['res_id']; ?>">
                        <div class="row">
                            <div class="total">
                                <div class="reservation-label">
                                    Tickets
                                </div>
                            </div>
                        </div>
                        <div class="ticket">
                            <div class="ticket-info">
                                <div class="tic_status row">
                                    <div class="ticket-label">
                                        Statut :
                                    </div>
                                    <div class="ticket-value">
                                        <?php $status = ($reservation['tic_is_used']) ? 'Utilisé' : 'Non utilisé' ?>
                                        <?php echo $status; ?>
                                    </div>
                                </div>
                                <div class="tic_firstname row">
                                    <div class="ticket-label">
                                        Prenom :
                                    </div>
                                    <div class="ticket-value">
                                        <?php echo $reservation['tic_firstname']; ?>
                                    </div>
                                </div>
                                <div class="tic_lastname row">
                                    <div class="ticket-label">
                                        Nom :
                                    </div>
                                    <div class="ticket-value">
                                        <?php echo $reservation['tic_lastname']; ?>
                                    </div>
                                </div>
                                <div class="tic_age row">
                                    <div class="ticket-label">
                                        Age :
                                    </div>
                                    <div class="ticket-value">
                                        <?php echo $reservation['tic_age']. ' ans'; ?>
                                    </div>
                                </div>
                                <div class="tic_price row">
                                    <div class="ticket-label">
                                        Prix :
                                    </div>
                                    <div class="ticket-value">
                                        <?php echo formatPrice($reservation['tic_price']).'€'; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="ticket-qr-code">
                                <img src="<?php echo base_url().'uploads/tickets/'.$reservation['tic_id']; ?>" alt="qrcode">
                                <?php if ($reservation['tic_note']): ?>
                                    <div class="ticket-note">
                                        <?php echo $reservation['tic_note'].'/10'; ?>
                                    </div>
                                <?php else: ?>
                                    <?php if ($reservation['tic_is_used'] && !$reservation['tic_is_gift'] || $reservation['tic_is_used'] && $reservation['tic_is_gift'] && $reservation['tic_email'] == $user['usr_email']): ?>
                                        <form action="evaluateActivityTicket" method="post">
                                            <input type="hidden" name="tic_id" value="<?php echo $reservation['tic_id']; ?>">

                                            <div class="field">
                                                <label for="tic_note">Note sur 10</label>
                                                <div class="control">
                                                    <input class="input" name="tic_note" type="number" min="0" max="10" required>
                                                </div>
                                            </div>

                                            <div class="field">
                                                <div class="control">
                                                    <div class="buttons">
                                                        <button class="button is-link evaluate-button">Evaluer</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php //@TODO one template foreach reservation types ?>
<?php if ($reservations): ?>
    <div class="reservations">
        <div class="reservation-section-title">
            Réservations à venir
        </div>
        <?php foreach ($reservations as $reservation): ?>
            <?php $this->load->view('reservation.php', array('reservation' => $reservation)); ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if ($passedReservations): ?>
    <div class="passed-resrvations">
        <div class="reservation-section-title">
            Réservations passées
        </div>
        <?php foreach ($passedReservations as $reservation): ?>
            <?php $this->load->view('reservation.php', array('reservation' => $reservation)); ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if ($cancelledReservations): ?>
    <div class="cancelled-resrvations">
        <div class="reservation-section-title">
            Réservations annulées
        </div>
        <?php foreach ($cancelledReservations as $reservation): ?>
            <?php $this->load->view('reservation.php', array('reservation' => $reservation)); ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<script>
    $('.modifyProfileButton').click(function () {
        $('.modifyProfileForm').toggleClass('hidden');
    });

    $('.modifyPasswordButton').click(function () {
        $('.modifyPasswordForm').toggleClass('hidden');
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

    $('.cancel-reservation_button').click(function () {
        let resId = this.id.replace('cancel-reservation_button-','');

        if (confirm( "Êtes-vous sûr de vouloir annuler cette réservation ?")) {
            window.location.replace("<?php echo base_url("ReservationController/cancelReservation"); ?>?id=" + resId);
        }
    });
</script>