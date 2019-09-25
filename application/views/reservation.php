<div class="reservation-card card">
    <div class="card-header">
        <div class="reservation-title"><?php echo $reservation['activity']['act_name']?> le <?php echo formatDateFromUsToFr($reservation['res_date']); ?> <?php echo formatTimeSlot($reservation['res_time_slot'])?></div>
        <?php if ($reservation['canCancelReservation']): ?>
            <button class="button cancel-reservation_button is-link" id="cancel-reservation_button-<?php echo $reservation['res_id']; ?>">Annuler la réservation</button>
        <?php endif; ?>
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
                        <div class="tic_status row">
                            <div class="ticket-label">
                                Statut :
                            </div>
                            <div class="ticket-value">
                                <?php $status = ($ticket['tic_is_used']) ? 'Utilisé' : 'Non utilisé' ?>
                                <?php echo $status; ?>
                            </div>
                        </div>
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
                        <img src="<?php echo base_url().'uploads/tickets/'.$ticket['tic_id']; ?>" alt="qrcode">
                        <?php if ($ticket['tic_note']): ?>
                            <div class="ticket-note">
                                <?php echo $ticket['tic_note'].'/10'; ?>
                            </div>
                        <?php else: ?>
                            <?php if ($ticket['tic_is_used'] && !$ticket['tic_is_gift'] || $ticket['tic_is_used'] && $ticket['tic_is_gift'] && $ticket['tic_email'] == $user['usr_email']): ?>
                                <form action="evaluateActivityTicket" method="post">
                                    <input type="hidden" name="tic_id" value="<?php echo $ticket['tic_id']; ?>">

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
            <?php endforeach; ?>
        </div>
    </div>
</div>